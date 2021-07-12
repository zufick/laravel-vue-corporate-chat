<?php

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

Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('canjoin')->group(function() {
        Route::get('messages/{room}', [\App\Http\Controllers\MessageController::class, 'index']);
        Route::post('messages/{room}', [\App\Http\Controllers\MessageController::class, 'store']);
    });
    Route::middleware('canmoderatemessage')->group(function() {
        Route::delete('message/{message}', [\App\Http\Controllers\MessageController::class, 'delete']);
    });

    Route::middleware('admin')->group(function() {
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
        Route::patch('user/{user}', [\App\Http\Controllers\UserController::class, 'update']);
        Route::delete('user/{user}', [\App\Http\Controllers\UserController::class, 'delete']);
    });
});
