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
        if ($response->successful()) {
            // Assuming the response is JSON
            // Process the received data as needed
            $token = @$response->json()['result']['token'];
            $hashAccessKey = md5($token . $this->credentials['accessKey']);
            $this->storeData($hashAccessKey, self::KEY_ACCESS_CACHE);
            $this->storeData($token, self::KEY_TOKEN_CACHE);
            return true;
        } else {
            // If the request fails, handle the error
            echo "ERROR: LOGIN FAILED";
            return false;
        }
    }

    public function getSessionId()
    {
        $accessKey = $this->getData(self::KEY_ACCESS_CACHE);
        $token = $this->getData(self::KEY_TOKEN_CACHE);

        if (!$token || !$accessKey) {
            return false;
        }
//        $queryParams = [
//            'module' => 'Reports',
//            'view' => 'ExportReport',
//            'mode' => 'GetCSV',
//            'record' => '230',
//        ];
//        $accessKey = 'sid:' . $sId . ',' . $token;
//        $data = [
//            '__vtrftk' => $accessKey,
////            'advanced_filter' => '{"1":{"columns":{"0":{"columnname":"vtiger_leadscf:cf_1221:Leads_TOG_Talent_Pool:cf_1221:C","comparator":"e","value":"0","column_condition":""}},"condition":"and"},"2":{"columns":{}}}',
//        ];

        $data = [
            "operation" => "login",
            "username" => $this->credentials['username'],
            "accessKey" => $accessKey
        ];

        $response = Http::asForm()->post($this->credentials['get_webservice_url'], $data);
        // Check if the request was successful
        if ($response->successful()) {
            // Assuming the response is JSON
            // Process the received data as needed
            $result = @$response->json()['result'];
            $this->sessionName = $result['sessionName'];
            $this->userId = $result['userId'];
            return true;
        } else {
            // If the request fails, handle the error
            echo "ERROR: SESSION NOT FOUND";
            return $response->status();
        }

    }

    public function getDataQuery($query)
    {
        $data = [
            "operation" => "query",
            "sessionName" => $this->sessionName,
            "query" => $query,
        ];

        $url = $this->credentials['get_webservice_url'] . '?' . http_build_query($data);

        $response = Http::withBasicAuth($this->credentials['username'], $this->credentials['password'])
            ->get($url);
        // Check if the request was successful
        if ($response->successful()) {
            // Assuming the response is JSON
            // Process the received data as needed
            return @$response->json();
        } else {
            // If the request fails, handle the error
            echo "ERROR";
            return false;
        }
    }
}
