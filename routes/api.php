<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
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
Route::middleware('auth:api')->group(function (){
    Route::get("/user",[UserController::class,"details"]);

});
Route::post("/register",[UserController::class,"registration"]);
Route::post("/login",[UserController::class,"login"]);
Route::get("/login",[UserController::class,"login"])->name('login');
Route::group(['prefix'=>'/place'],function(){
    Route::post("add",[PlaceController::class,"addPlace"]);
});
Route::group(['prefix'=>'/review'],function(){
    Route::post("add",[ReviewController::class,"addReview"]);
});
