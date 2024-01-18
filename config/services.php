<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    # Account of user in CRM website
    $crm = 'http://localhost:8000/',
    'crm' => [
        'username' => 'long',
        'password' => '123456',
        'base_url' => $crm,
        'index_url' => $crm . 'index.php',
        'get_leads_url' => $crm . 'tog_apps/get_leads_data_info.php',
        'get_webservice_url' => $crm . 'webservice.php',
        'get_export_data' => $crm . 'tog_apps/talent_pool/get_export_data.php',
        'accessKey'=> "4itWgheHtEDDRKda",
//        'accessKey'=> "fPPIsCmerE6ZVft3"

    ],
    'lead_fields' =>[
        'is_talent_pool'=> "cf_1221",
        'phone' => 'mobile',
        'province' => 'cf_792',
        'city' => 'city',
        'birthdate' => 'cf_790',
        'company_id' => 'company',
        'english' => 'cf_1211',
        'linkedin' => 'cf_1097',
        'salary' => 'cf_842',
        'expect' => 'cf_866',
        'experience' => 'cf_1167',
        'description' => 'cf_1167',
        'skill' => 'cf_1155',
        'position' => 'cf_826',
        'crm_id' => 'id',
    ],
    'organization_fields' =>[
        'name'=> "accountname",
        'account_no' => 'account_no',
        'phone' => 'phone',
        'email' => 'email1',
        'website' => 'website',
        'description' => 'description',
        'city' => 'bill_city',
        'province' => 'cf_788',
        'crm_id' => 'id',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
