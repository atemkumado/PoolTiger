<?php

namespace App\Services;

use App\Models\Location\Province;
use App\Models\Talent;
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

        Log::debug("VTIGER SERVICE");


    }

    public function loadSession()
    {
        $this->login();
        $this->getSessionId();
//        $this->getListTypes();
    }

    // store token and access key in cache
    public function login()
    {
        $data = [
            "operation" => "getchallenge",
            "username" => $this->credentials['username']
        ];
        $url = $this->credentials['get_webservice_url'] . '?' . http_build_query($data);

        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
            ->get($url);
        // Check if the request was successful
        if ($response->failed() || !@$response->json()["success"]) {
            echo @$response->json()["error"]["message"] ?? "ERROR: LOGIN FAILED";
            return $response->status();
        }

        // Assuming the response is JSON
        // Process the received data as needed
        $token = @$response->json()['result']['token'];
        $hashAccessKey = md5($token . $this->credentials['accessKey']);
        $this->storeData($hashAccessKey, self::KEY_ACCESS_CACHE);
        $this->storeData($token, self::KEY_TOKEN_CACHE);
        return true;

    }


    public function getSessionId()
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


    public function getLeadInfo()
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


    /**
     * @throws Exception
     */
    public function fetchExportFile()
    {
        $info = $this->getLeadInfo(); // Get $field and $fieldMapping

        if (!$info) {
            return false;
        }
        $field = $info['vtigerFieldArr'];
        $mappings = $info['mappings'] ?? [];
        $fieldMappings = [];
        foreach ($mappings as $key => $value) {
            $fieldMappings[$value] = $key;
        }
//        echo json_encode($fieldMappings, JSON_PRETTY_PRINT);
//        die;
        // GET LEAD DATA
        $talentPoolField = $this->credentials['talentPoolField'];
        $query = "SELECT * FROM Leads";
//        $query = "SELECT * FROM Leads WHERE $talentPoolField != 0";
        $data = $this->getDataQuery($query) ?? [];

        if (count($data) == 0) {
            return false;
        }
        // HANDLE HEADER
        $headers = array_keys($data[0]);

        $convertedHeader = [];
        foreach ($headers as $value) {
            $convertedHeader[] = $fieldMappings[$value] ?? $value;
        }

        $inserted = $this->updateTalents($data);
        $this->saveDataToExcel($data, $convertedHeader);
        return $inserted;
    }

    function updateTalents($data): array
    {
        $insertedTalents = [];
        $provinces = [];
        foreach ($data as $value) {
            // Handle Provinces
            $province = $this->handleProvince((!$value['city']) ?  $value['cf_792'] : $value['city']);
            if(!array_key_exists($province, $provinces)){
                $provinces[$province] = Province::where('name_en',$province)->value('id') ?? '';
            }

            // Build data
            $insertedTalents[] = [
                "firstname" => @$value['firstname'],
                "lastname" => @$value['lastname'],
                "lead_no" => @$value['lead_no'],
                "name" => $value['firstname'] . " " . $value['lastname'] ?? "",
                "email" => $value['email'] ?? "",
                "birthdate" => $value['cf_790'] ?? "",
                "phone" => $value['mobile'] ?? "",
                "english" => Talent::ENGLISH_LEVEL_TITLE[$value['cf_1211']] ?? "",
                "linkedin" => $value['cf_1097'] ?? "",
//                "facebook" => $value['firstname'],
//                "github" => $value['firstname'],
                "salary" => $value['cf_842'] ?? "",
                "expect" => $value['cf_866'] ?? "",
                "experience" => $value['cf_1167'] ?? "",
//                "description" => $value['cf_1167'] ?? "",
                "province_id" =>   $provinces[$province],
                "crm_id" =>   $value['id'] ?? "",
                "is_tp" =>   $value['cf_1221'] ?? "",
            ];
        }
//        DB::table('talents')->insert($insertedTalents);
//        return $insertedTalents;
            
        return true;
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
        $patterns = ['province','city','province']; // Words to remove
// Create a regular expression pattern to match any of the words in $patterns
        $pattern = '/\b(' . implode('|', $patterns) . ')\b/i';
// Remove matched words from the string
        $result = preg_replace($pattern, '', $string);
// Trim any extra spaces after removal
        $result = trim(preg_replace('/\s+/', ' ', $result)) ?? '';
        return ucwords(strtolower($result));
    }
}
