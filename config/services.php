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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'sso-siasn' => [
        'client_id' => env('SSO_SIASN_CLIENT_ID'),
        'grant_type' => env('SSO_SIASN_GRANT_TYPE', 'password'),
        'username' => env('SSO_SIASN_USERNAME'),
        'password' => env('SSO_SIASN_PASSWORD'),
        'token_url' => env('SSO_SIASN_TOKEN_URL')
    ],
    'apimws-bkn' => [
        'username' => env('APIMWS_BKN_USERNAME'),
        'password' => env('APIMWS_BKN_PASSWORD'),
        'base_url' => env('APIMWS_BKN_BASE_URL'),
        'token_url' => env('APIMWS_BKN_TOKEN_URL'),
        'reference_url' => env('APIMWS_BKN_REFERENCE_URL'),
    ]
];
