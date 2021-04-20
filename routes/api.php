<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function() {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'authenticate']);
});

Route::middleware('auth:api')->group(function() {
    Route::prefix('qrCode')->group(function() {
        Route::get('card/{card}', [QrCodeController::class, 'generateByCard']);
        Route::get('field/{field}', [QrCodeController::class, 'generateByField']);
    });

    Route::prefix('user')->group(function() {
        Route::get('/', [UserController::class, 'show']);
        Route::apiResource('cards', CardController::class);
    });
});
