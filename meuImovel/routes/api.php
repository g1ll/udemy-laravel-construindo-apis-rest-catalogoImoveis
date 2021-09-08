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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function (){
    Route::prefix('real-states')->name('real_states')->group(function (){
        Route::get('/',[\App\Http\Controllers\Api\RealStateController::class,'index']); #/api/v1/real-states/
        Route::post('/',[\App\Http\Controllers\Api\RealStateController::class,'save'])->middleware('auth.basic'); #/api/v1/real-states/
    });
});
