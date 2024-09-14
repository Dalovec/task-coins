<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $coinGeckoApi = new \App\CoinGeckoApi();
    dd($coinGeckoApi->ping());
});
