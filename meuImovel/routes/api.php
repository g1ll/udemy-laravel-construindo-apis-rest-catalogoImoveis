<?php

use App\Http\Controllers\Api\Auth\LoginJwtController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RealStatePhotoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RealStateController;

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

Route::prefix('v1')->group(function (){
    Route::post('/login',[LoginJwtController::class,'login'])->name('login');

    Route::name('real_states.')->group(function (){
//        Route::get('/',[RealStateController::class,'index']); #/api/v1/real-states/
//        Route::get('/{real_state}',[RealStateController::class,'show']);
//        Route::post('/',[RealStateController::class,'save'])->middleware('auth.basic'); #/api/v1/real-states/
//        Route::delete('/{real_state}',[RealStateController::class,'remove'])->middleware('auth.basic'); #/api/v1/real-states/id
//        Route::put('/{real_state}',[RealStateController::class,'update'])->middleware('auth.basic'); #/api/v1/real-states/id
        Route::apiResource('real-states',RealStateController::class);
    });

    Route::name('users.')->group(function (){
        Route::apiResource('users',UserController::class);
    });

    Route::name('categories.')->group(function (){
        Route::apiResource('categories',CategoryController::class);
        Route::get('categories/{id}/real-states',[CategoryController::class,'realStates']);
    });

    Route::name('photos.')->prefix('photos')->group(function(){
        Route::delete('/{photoId}',[RealStatePhotoController::class,'destroy'])->name('delete');
        Route::put('/set-thumb/{photoId}/{realStateId}',[RealStatePhotoController::class,'setThumb'])->name('setThumb');
    });


});
