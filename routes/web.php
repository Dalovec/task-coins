<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/issue-token', [AuthController::class, 'issueToken']);
    Route::get('/revoke-token', [AuthController::class, 'revokeToken']);
});
