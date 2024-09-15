<?php

use App\Http\Controllers\CoinController;
use App\Http\Controllers\WatchDogController;
use App\Http\Middleware\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/currencies', [CoinController::class, 'index'])->name('coins.index');
Route::get('/currencies/{coin}', [CoinController::class, 'show'])->name('coins.show');

Route::middleware([JWTAuth::class])->group(function () {

    Route::get('/watch-dogs', [WatchDogController::class, 'index']);
    Route::post('/create-watch-dog', [WatchDogController::class, 'create']);
    Route::delete('/delete-watch-dog/{coin_id}', [WatchDogController::class, 'destroy']);

});
