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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'igdb' => [
        'headers' => [
            'Client-ID' => env('IGDB_CLIENT_ID'),
            'Authorization' => 'Bearer '.env('IGDB_ACCESS_TOKEN'),
        ],
        'endpoint' => 'https://api.igdb.com/v4/games',
        'authentication_url'=> 'https://id.twitch.tv/oauth2/token'
    ],
    'movie' => [
        'headers' => [
            'X-RapidAPI-Host' => env('RAPID_API_HOST'),
            'X-RapidAPI-Key' => env('RAPID_API_KEY'),
        ],
    ]

];
