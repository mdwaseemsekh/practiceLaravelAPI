<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,"register"]);
Route::post('/login',[AuthController::class,"login"]);
Route::post('/logout',[AuthController::class,"logout"])->middleware('auth:sanctum');

Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::get('/all_users',[UserController::class,"show"]);
    Route::post('/add-user',[UserController::class,"addUser"]);
    Route::put('/update/{id}',[UserController::class,"updateUser"]);
});

Route::get('/login',[AuthController::class,'login'])->name('login');
