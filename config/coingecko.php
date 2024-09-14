<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CoinGecko API Key
    |--------------------------------------------------------------------------
    |
    | Your CoinGecko API key.
    |
    */

    'api_key' => env('COINGECKO_API_KEY'),

    'base_url' => env('COINGECKO_BASE_URL', 'https://api.coingecko.com/api/v3/'),
];
