<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[UserController::class,'store']);

Route::post('/login',[UserController::class,'login']);

Route::middleware(['session_check','role_check'])->group(function(){

    Route::post('/properties/store',[PropertyController::class,'store']);

    Route::get('/data',function(){
        return "Hello";
    });
});