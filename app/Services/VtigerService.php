<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Location\Province;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use App\Models\TalentSkill;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class VtigerService extends Service
{
    private $sessionName;
    private $userId; //hashed user id

    public function __construct()
    {
        parent::__construct();
        $this->loadSession();
        Log::debug("VTIGER SERVICE");
    }

    public function loadSession(): void
    {
        $isToken = $this->getToken();
        if (!$isToken) exit;
        $this->getSession();
//        $this->getListTypes();
    }

    // store token and access key in cache
    public function getToken()
    {
        $data = [
            "operation" => "getchallenge",
            "username" => $this->credentials['username']
        ];
        $url = $this->credentials['get_webservice_url'] . '?' . http_build_query($data);

        $response = Http::timeout(600)->get($url);
        // Check if the request was successful
        if ($response->failed() || !@$response->json()["success"]) {
            echo @$response->json()["error"]["message"] ?? "ERROR: LOGIN FAILED";
            return false;
        }

        // Assuming the response is JSON
        // Process the received data as needed
        $token = @$response->json()['result']['token'];
        $hashAccessKey = md5($token . $this->credentials['accessKey']);
        $this->storeData($hashAccessKey, self::KEY_ACCESS_CACHE);
        $this->storeData($token, self::KEY_TOKEN_CACHE);
        return true;

    }


    public function getSession(): bool
    {
        $accessKey = $this->getData(self::KEY_ACCESS_CACHE);
        $token = $this->getData(self::KEY_TOKEN_CACHE);

        if (!$token || !$accessKey) {
            return false;
        }

        $data = [
            "operation" => "login",
            "username" => $this->credentials['username'],
            "accessKey" => $accessKey
        ];

        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])->asForm()->post($this->credentials['get_webservice_url'], $data);
        // Check if the request was successful
        if ($response->failed() || !@$response->json()["success"]) {
            echo @$response->json()["error"]["message"] ?? "ERROR: SESSION NOT FOUND";
            return false;
        }

        // Assuming the response is JSON
        // Process the received data as needed
        $result = @$response->json()['result'];
        $this->sessionName = $result['sessionName'];
        $this->userId = $result['userId'];
        return true;

    }


    public function getFieldInfo()
    {
        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
            ->get($this->credentials["get_leads_url"]);

        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return false;
        }
        return @$response->json();
    }

    public function getExport()
    {
        $queryParams = [
            'operation' => 'getReport',
            'sessionName' => $this->sessionName,
            'id' => 230,
        ];
        $response = Http::post($this->credentials['get_webservice_url'], $queryParams);

        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return $response->status();
        }
        $csvData = $response->body();
        file_put_contents('exported_data.csv', $csvData);
//        $result = @$response->json();
        echo json_encode($response->json());
//        echo json_encode($result);

        // Assuming the response is JSON
        // Process the received data as needed

        return @$csvData;
    }

    public function getListTypes()
    {

        $queryParams = [
            'operation' => 'listtypes',
            'sessionName' => $this->sessionName,
        ];
        $response = Http::get($this->credentials['get_webservice_url'], $queryParams);

        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return $response->status();
        }
        $data = $response->json();
        return @$data;
    }

    public function getDataQuery($query)
    {
        $allRecords = [];
        $limit = 100; // Set your desired batch size
        $offset = 0;

        do {
            $queryParams = [
                "operation" => "query",
                "sessionName" => $this->sessionName,
                "query" => "$query LIMIT $offset,$limit ;",
            ];
            $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
                ->get($this->credentials['get_webservice_url'], $queryParams);

            if ($response->failed() || !@$response->json()["success"]) {
                echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
                return false;
            }
            $records = @$response->json()['result'];
            if (empty($records)) {
                break; // No more records, exit loop
            }

            $allRecords = array_merge($allRecords, $records);
            $offset += $limit;
        } while (true);

        // Fetching data in batches with a limit

        return $allRecords;
    }

    const TALENT = 'talent';
    const COMPANY = 'organization';
    const SKILL = 'skill';
    const POSITION = 'position';

    /**
     * Using Query Webservice to fetch Data from Vtiger Website
     * 1. Save the response data to Excel file in public location
     * 2. Validate and update Talent Pool Database
     * @throws Exception
     * @author Long
     */
    public function fetchData()
    {
        $crmFields = config('services.lead_fields');
        $fieldMappings = $this->getFields();
        // GET LEAD DATA
        $talentPoolField = $crmFields['is_talent_pool'];
        $query = "SELECT * FROM Leads";
//        $query = "SELECT * FROM Leads WHERE $talentPoolField != 0";
        $leads = $this->getDataQuery($query) ?? [];

        if (count($leads) == 0) {
            return false;
        }

        // HANDLE HEADER
//        $headers = array_keys($leads[0]);

//        $convertedHeader = [];
//        foreach ($headers as $value) {
//            $convertedHeader[] = $fieldMappings[$value] ?? $value;
//        }

//        echo json_encode($leads, JSON_PRETTY_PRINT); die;
        $inserted = $this->processUpdate($leads);
//        $this->saveDataToExcel($leads, $convertedHeader);
        return $inserted;
    }

    /**
     * @throws \Exception
     */
    function processUpdate($data): bool|array
    {
        $data = $this->normalize($data);
//        echo json_encode($data, JSON_PRETTY_PRINT); die;

        $this->updateTalents($data);
        return $data;
    }

    function normalize($data): array|bool
    {
        $insertedTalents = [];
        $provinces = [];
        $companyIds = [];
        $skillIds = [];
        $positionIds = [];
        $talentFields = config('services.lead_fields');
        $companyFields = config('services.organization_fields');

        foreach ($data as $value) {
            // Handle Provinces
            $province = (!$value[$talentFields['city']]) ? $value[$talentFields['province']] : $value[$talentFields['city']];
            $province = $this->handleProvince($province);
            $companyId = $value[$talentFields['company_id']];
            $skillId = $value[$talentFields['skill']];
            $positionId = $value[$talentFields['position']];
            if (!in_array($positionId, $positionIds)) {
                $positionIds[] = $positionId;
            }
            if (!in_array($skillId, $skillIds)) {
                $skillIds[] = $skillId;
            }
            if (!in_array($companyId, $companyIds)) {
                $companyIds[] = $companyId;
            }
            if (!array_key_exists($province, $provinces)) {
                $provinces[$province] = Province::where('name_en', $province)->value('id') ?? null;
            }
            // Build data
            $insertedTalents[] = [
                "firstname" => $value['firstname'],
                "lastname" => $value['lastname'],
                "lead_no" => $value['lead_no'],
                "name" => $value['firstname'] . " " . $value['lastname'] ?? "",
                "email" => $value['email'] ?? "",
                "birthdate" => $value[$talentFields['birthdate']] ?? "",
                "phone" => $value[$talentFields['phone']] ?? "",
                "english" => Talent::ENGLISH_LEVEL_TITLE[$value[$talentFields['english']]] ?? "",
                "linkedin" => $value[$talentFields['linkedin']] ?? "",
                "company_id" => $value[$talentFields['company_id']] ?? "",
//                "facebook" => $value['facebook'],
//                "github" => $value['github'],
                "salary" => $value[$talentFields['salary']] ?? "",
                "expect" => $value[$talentFields['expect']] ?? "",
                "experience" => $value[$talentFields['experience']] ?? "",
                "skill" => $skillId ?? "",
                "position" => $positionId ?? "",
//                "description" =>  $value[$talentFields['description']] ?? "",
                "province_id" => $provinces[$province],
                "crm_id" => $value[$talentFields['crm_id']] ?? "",
                "is_talent_pool" => $value[$talentFields['is_talent_pool']] ?? "",
            ];

        }
        // GET COMPANY BY VTIGER REST API
        $companyIds = trim(implode(',', $companyIds), ',');
        $query = "SELECT * FROM Accounts WHERE id IN ( $companyIds )";
        $companies = $this->getDataQuery($query) ?? [];
        if (count($companies) == 0) {
            return false;
        }

        $insertedCompany = [];
        foreach ($companies as $value) {
            $province = (!$value[$companyFields['city']]) ? $value[$companyFields['province']] : $value[$companyFields['city']];
            $province = $this->handleProvince($province);
            if (!array_key_exists($province, $provinces)) {
                $provinces[$province] = Province::where('name_en', $province)->value('id') ?? null;
            }
            $insertedCompany[] = [
                "name" => $value[$companyFields['name']],
                "account_no" => $value[$companyFields['account_no']],
                "phone" => $value[$companyFields['phone']],
                "email" => $value[$companyFields['email']],
                "website" => $value[$companyFields['website']],
                "description" => $value[$companyFields['description']],
                "province_id" => $provinces[$province],
                "crm_id" => $value[$companyFields['crm_id']],
            ];
        }
        return [
            self::TALENT => $insertedTalents,
            self::COMPANY => $insertedCompany,
            self::SKILL => $skillIds,
            self::POSITION=> $positionIds
        ];
    }

    /**
     * @throws \Exception
     */
    private function handleCompany($companies): void
    {
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Assume $dataList is the list of data to be validated, updated, or deleted

            foreach ($companies as $company) {
                // Validate data against the database table
                $existingRecord = Company::where('crm_id', $company['crm_id'])->first();
                if ($existingRecord) {
                    Debugbar::info("UPDATE COMPANY");
                    Debugbar::info($company);
                    $existingRecord->update($company); // Replace with your update logic
                } else {
                    Debugbar::info("INSERT COMPANY");
                    Debugbar::info($company);
                    Company::create($company);
                }
            }
            $deletedCompanyIds = Company::whereNotIn('crm_id', array_column($companies, 'crm_id'))->pluck('id');
            Debugbar::info("DELETE COMPANY");
            foreach ($deletedCompanyIds as $companyId) {
                Debugbar::info($companyId);
                Talent::where('company_id', $companyId)->delete();
            }
            Company::whereIn('id', $deletedCompanyIds)->delete();
            // Commit the transaction if everything is successful
            DB::commit();

        } catch (\Exception $e) {
//             Handle exceptions, log errors, or roll back the transaction on failure
            DB::rollBack();
            throw new \Exception("Error: $e.");
        }
    }

    private function handleTalent($talents): void
    {
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Assume $dataList is the list of data to be validated, updated, or deleted

            foreach ($talents as $talent) {
                $companyId = Company::where('crm_id', $talent['company_id'])->value('id') ?? NULL;
                $skillId = Skill::where('name', $talent['skill'])->value('id') ?? NULL;
                $positionId = Position::where('name', $talent['position'])->value('id') ?? NULL;

                // Validate data against the database table
                $talent['company_id'] = $companyId;
                $talent['skill_id'] = $skillId;
                $existingRecord = Talent::where('crm_id', $talent['crm_id'])->first();
                if ($existingRecord) {
                    Debugbar::info("UPDATE TALENT");
                    Debugbar::info($talent);
                    $existingRecord->update($talent); // Replace with your update logic
                    // CHECK EXIST SKILL IN TALENT
                    if ($existingRecord->skill->contains($skillId)) {
                        $existingRecord->skill()->updateExistingPivot($skillId, ['is_best' => true]);
                    } else {
                        $existingRecord->skill()->attach($skillId, ['is_best' => true]);
                    }
                    if ($existingRecord->position->contains($positionId)) {
                        $existingRecord->position()->sync($positionId);
                    } else {
                        $existingRecord->position()->attach($positionId);
                    }

                } else {
                    Debugbar::info("INSERT TALENT");
                    Debugbar::info($talent);
                    $talentId =Talent::create($talent)->id;
                    $newTalent = Talent::find($talentId);
                    $newTalent->skill()->attach($skillId, ['is_best' => true]);
                }

            }
            Talent::whereNotIn('crm_id', array_column($talents, 'crm_id'))->delete();
            // Commit the transaction if everything is successful
            DB::commit();

        } catch (\Exception $e) {
//             Handle exceptions, log errors, or roll back the transaction on failure
            DB::rollBack();
            throw new \Exception("Error: $e.");
        }
    }

    private function handleSkill($skills): void
    {
//        echo  json_encode($skills, JSON_PRETTY_PRINT); die;
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Assume $dataList is the list of data to be validated, updated, or deleted
            foreach ($skills as $skillName) {
                if(!$skillName) continue;
                $skillId = Skill::where('name', $skillName)->value('id') ?? NULL;
                // Validate data against the database table
                if (!$skillId) {
                    Debugbar::info("INSERT SKILL");
                    Debugbar::info($skillName);
                    $skillName = ['name' => $skillName];
                    Skill::create($skillName);
                }
            }
            Skill::whereNotIn('name', $skills)->delete();
            // Commit the transaction if everything is successful
            DB::commit();

        } catch (\Exception $e) {
//             Handle exceptions, log errors, or roll back the transaction on failure
            DB::rollBack();
            throw new \Exception("Error: $e.");
        }
    }

    private function handlePosition($positions): void
    {
//        echo  json_encode($skills, JSON_PRETTY_PRINT); die;
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Assume $dataList is the list of data to be validated, updated, or deleted
            foreach ($positions as $positionName) {
                if(!$positionName) continue;
                $skillId = Position::where('name', $positionName)->value('id') ?? NULL;
                // Validate data against the database table
                if (!$skillId) {
                    Debugbar::info("INSERT SKILL");
                    Debugbar::info($positionName);
                    $positionName = ['name' => $positionName];
                    Position::create($positionName);
                }
            }
            Position::whereNotIn('name', $positions)->delete();
            // Commit the transaction if everything is successful
            DB::commit();

        } catch (\Exception $e) {
//             Handle exceptions, log errors, or roll back the transaction on failure
            DB::rollBack();
            throw new \Exception("Error: $e.");
        }
    }

    /**
     * @throws \Exception
     */
    function updateTalents($data): void
    {
//        echo json_encode($data, JSON_PRETTY_PRINT); die;
        // HANDLE SKILL
        $this->handleSkill($data[self::SKILL]);
        // HANDLE POSITION
        $this->handlePosition($data[self::POSITION]);
        // HANDLE COMPANY
        $this->handleCompany($data[self::COMPANY]);
        // HANDLE TALENTS
        $this->handleTalent($data[self::TALENT]);


    }

    function saveDataToExcel($data, $headers): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers (if needed)
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            ++$column;
        }
        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FF8000', // Red color, you can use other RGB values
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFDF6', // You can replace COLOR_BLUE with your desired color
                ],
            ],
        ];
        $column = --$column;
        $sheet->getStyle('A1:' . $column . '1')->applyFromArray($styleArray);
        // Write data to the cells
        $row = 2;
        foreach ($data as $row_data) {
            $column = 'A';
            foreach ($row_data as $value) {
                $sheet->setCellValue($column . $row, $value);
                $column++;
            }
            $row++;
        }
        $this->setFormat($sheet, $row);
        // Save the Excel file
        $writer = new Xlsx($spreadsheet);
        $fileName = "data_" . date('dmY_His') . ".xlsx";
        $file_path = public_path("upload/$fileName"); // Change the path as needed
        $writer->save($file_path);

        return $file_path; // Return the file path or any other indicator
    }

    public function setFormat($sheet, $row, $begin = 'A', $end = 'CP'): void
    {
        $sheet->getStyle($begin . "1:$end" . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => ['size' => 12,],
        ]);

    }

    public function handleProvince($string)
    {
        $string = strtolower($string);
        $patterns = ['province', 'city']; // Words to remove
        // Create a regular expression pattern to match any of the words in $patterns
        $pattern = '/\b(' . implode('|', $patterns) . ')\b/i';
        // Remove matched words from the string
        $result = preg_replace($pattern, '', $string);
        // Trim any extra spaces after removal
        $result = trim(preg_replace('/\s+/', ' ', $result)) ?? '';
        return ucwords($result);
    }

    public function getExportData()
    {
        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
            ->get($this->credentials["get_export_data"]);

        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return false;
        }
        return @$response->json();
    }
    public function getFields(){
        $info = $this->getFieldInfo(); // Get $field and $fieldMapping
//        echo json_encode($info, JSON_PRETTY_PRINT);
//        die;
        if (!$info) {
            return false;
        }
        $mappings = $info['mappings'] ?? [];
        $fieldMappings = [];
        foreach ($mappings as $key => $value) {
            $fieldMappings[$value] = $key;
        }
        return $fieldMappings;
    }

    /**
     * @throws \Exception
     */
    public function update(){
        $data = $this->getExportData();
        if (!$data) {
            return false;
        }
        $headers = array_shift($data);

        $importData = [];
        foreach ($data as $row) {
            $rowData = [];
            foreach ($headers as $key => $header) {
                // Ensure that the key exists in the current row
                if (isset($row[$key])) {
                    $rowData[$header] = $row[$key];
                } else {
                    // Handle the case where the key doesn't exist in the current row
                    $rowData[$header] = null;
                }
            }
            $importData[] = $rowData;
        }
        return $this->processUpdate($importData);
    }


}
