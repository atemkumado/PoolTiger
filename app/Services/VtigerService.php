<?php

namespace App\Services;

use App\Constants\VtigerConstant;
use App\Http\Requests\TalentRequest;
use App\Models\Location\Province;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VtigerService extends Service
{
    private $sessionName;
    private $userId; //hashed user id
    private $sessionId; //hashed user id
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
    private function extractSessionId($setCookieHeader)
    {
        // Logic to extract PHPSESSID from Set-Cookie header
        // You may need to parse the header to get the PHPSESSID value
        // Example logic:
        $matches = [];
        if (preg_match('/PHPSESSID=([^;]+)/', $setCookieHeader, $matches)) {
            return $matches[1]; // PHPSESSID value extracted
        } else {
            return 'Unable to extract PHPSESSID';
        }
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

        $response = Http::asForm()->post($this->credentials['get_webservice_url'], $data);
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

    public function createAPI($element)
    {
        $accessKey = $this->getData(self::KEY_ACCESS_CACHE);
        $data = [
            "operation" => "create",
            "username" => $this->credentials['username'],
            "accessKey" => $accessKey
        ];

        $response = Http::asForm()->post($this->credentials['get_webservice_url'], $data);
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
        $sessionId = $this->sessionName;
        echo json_encode($sessionId);
        die;
//        $token = $this->getData(self::KEY_TOKEN_CACHE);
        $queryParams = [
            'module' => 'Reports',
            'view' => 'ExportReport',
            'mode' => 'GetXLS',
            'record' => '230',
        ];
        $url = 'http://localhost:8000/index.php?module=Reports&view=ExportReport&mode=GetCSV&record=230';
//        $url = $this->credentials['index_url'] . '?' . http_build_query($queryParams);

//        $accessKey = 'sid:' . $sId . ',' . $token;
//        $accessKey = 'sid:dedd905113c35602d572b2f39a65652cc0ea8194,1704272585';

//        $data = [
//            '__vtrftk' => $accessKey,
//            'advanced_filter' => '{"1":{"columns":{},"condition":"and"},"2":{"columns":{"0":{"columnname":"vtiger_leadscf:cf_1221:Leads_TOG_Talent_Pool:cf_1221:C","comparator":"e","value":"1","column_condition":""}}}}'
//        ];
        $sessionId = '7040e1a465  9781ef1c059';
        $response = Http::withHeaders([
            'Cookie' => "PHPSESSID=$sessionId", // Set the PHPSESSID in the Cookie header
            // Add other headers if needed
        ])->get($url);
        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return $response->status();
        }
        $csvData = $response->body();
        file_put_contents('exported_data.csv', $csvData);
        $result = @$response->json();
        echo json_encode($csvData);
        echo json_encode($result);

        // Assuming the response is JSON
        // Process the received data as needed

//        return @$result;
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
    private $field;
    private $fieldMapping;

    public function fetchingData()
    {
        $info = $this->getLeadInfo(); // Get $field and $fieldMapping
//        echo json_encode($info);
        if (!$info) {
            return false;
        }
        $this->field = $info['vtigerFieldArr'];
        $this->fieldMapping = $info['mappings'];

        // Fetching User and Groups
        $userQuery = "SELECT * FROM Users ";
        $groupQuery = "SELECT * FROM Users ";
        $users = $this->getDataQuery($userQuery);
        $groups = $this->getDataQuery($groupQuery);


        $skills = VtigerConstant::SKILL_FIELDS;
        $skillFields = [];
        foreach ($skills as $skill) {
            $skillField = @$this->fieldMapping[$skill];
            if (isset($skillField)) {
                array_push($skillFields, $skillField);
            }
        }

        echo json_encode($skillFields, JSON_PRETTY_PRINT);
//        echo json_encode($this->fieldMapping, JSON_PRETTY_PRINT);
        return true;
    }

    public function fetchExportFile()
    {
        $queryParams = [
            'module' => 'Reports',
            'view' => 'ExportReport',
            'mode' => 'GetXLS',
            'record' => '230',
        ];
        $url = $this->credentials["base_url"] . 'tog_apps/get_talent_pool_data.php';
//        $url = 'http://localhost:8000/tog_apps/get_talent_pool_data.php';
        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
            ->get($url, $queryParams);

        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return false;
        }
        $csvData = $response->body();
        // Process the CSV data or save it to a file
        // For example, save to a file named 'exported_data.csv'
        file_put_contents('exported_data.csv', $csvData);
        return 'CSV exported successfully!';
//        echo json_encode($response->body());
//        return @$response->json();
    }
}
