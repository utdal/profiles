<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
    'allowedOrigins' => explode(' ', env('API_ALLOWED_ORIGINS', 'https://example.com example.com *.example.com *.test')),
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => [
        'Content-Type',
        'X-Auth-Token',
        'Origin',
    ],
    'allowedMethods' => [
        'GET',
    ],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
