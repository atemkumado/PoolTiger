<?php

namespace App\Services;

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
    public $sessionName;
    public $userId; //hashed user id

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

        $response = Http::asForm()->post($this->credentials['get_webservice_url'], $data);
        // Check if the request was successful
        if ($response->failed() || !@$response->json()["success"]) {
            echo @$response->json()["error"]["message"] ?? "ERROR: SESSION NOT FOUND";
            return $response->status();
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
        if ($response->failed() || !@$response->json()["success"]) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return $response->status();
        }
        return @$response->json();
    }

    public function getExport()
    {
        $sId = $this->sessionName;
        $token = $this->getData(self::KEY_TOKEN_CACHE);
        $queryParams = [
            'module' => 'Reports',
            'view' => 'ExportReport',
            'mode' => 'GetXLS',
            'record' => '230',
        ];
        $url = $this->credentials['index_url'] . '?' . http_build_query($queryParams);

//        $accessKey = 'sid:' . $sId . ',' . $token;
        $accessKey = 'sid:dedd905113c35602d572b2f39a65652cc0ea8194,1704272585';

        $data = [
            '__vtrftk' => $accessKey,
            'advanced_filter' => '{"1":{"columns":{},"condition":"and"},"2":{"columns":{"0":{"columnname":"vtiger_leadscf:cf_1221:Leads_TOG_Talent_Pool:cf_1221:C","comparator":"e","value":"1","column_condition":""}}}}'
        ];

        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
            ->post($url, $data);
        if ($response->failed()) {
            echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
            return $response->status();
        }
        $result = @$response;
        echo json_encode($result);

        // Assuming the response is JSON
        // Process the received data as needed

        return @$result;
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
                "query" => "SELECT * FROM Leads LIMIT $offset,$limit ;",
            ];
            $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
                ->get($this->credentials['get_webservice_url'], $queryParams);

            if ($response->failed() || !@$response->json()["success"]) {
                echo @$response->json()["error"]["message"] ?? "ERROR: FAILED";
                return $response->status();
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
}
