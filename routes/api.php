<?php

use App\Http\Controllers\CoinController;
use App\Http\Middleware\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/coins', [CoinController::class, 'index'])->name('coins.index');
Route::get('/coins/{coin}', [CoinController::class, 'show'])->name('coins.show');

Route::middleware([JWTAuth::class])->group(function () {

    Route::get('/watch-dogs', function (Request $request){
        dd($request->user());
    });

});
