<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CORS is handled at NGINX edge layer
    |--------------------------------------------------------------------------
    |
    | Keep Laravel CORS middleware effectively disabled to avoid duplicate
    | Access-Control-* headers (browser rejects multiple ACAO values).
    |
    */
    'paths' => [],
    'allowed_methods' => ['*'],
    'allowed_origins' => [],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
