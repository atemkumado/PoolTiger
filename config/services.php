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
        'accessKey'=> "4itWgheHtEDDRKda"
//        'accessKey'=> "fPPIsCmerE6ZVft3"

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
