<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishController;
use \App\Http\Controllers\StatesController;
use App\Http\Controllers\PasswordResetController;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', [PasswordResetController::class,"create"]);
});

Route::middleware('auth:api')->group(function (){
    Route::group(['prefix'=>'/user'],function(){
        Route::get("",[UserController::class,"details"]);
        Route::post("/updatepicture",[UserController::class,"updateProfilePicture"]);
        Route::post("/logout",[UserController::class,"logout"]);
        //Route::post("add",[ReviewController::class,"addReview"]);
    });
    Route::group(['prefix'=>'/review'],function(){
        Route::post("add",[ReviewController::class,"addReview"]);
        Route::get("{id}",[ReviewController::class,"get"]);

    });
    Route::group(['prefix'=>'/place'],function(){
        Route::post("add",[PlaceController::class,"addPlace"]);
        Route::put("updateinfo/{id}",[PlaceController::class,"updatePlaceInfo"]);
        Route::get("all",[PlaceController::class,"all"]);
        Route::get("{id}",[PlaceController::class,"get"]);

        // Route::put("updatepictures/{id}",[PlaceController::class,"updatePlacePictures"]);

    });
    Route::post("/wish/add",[WishController::class,"add"]);
    Route::delete("/wish/delete",[WishController::class,"delete"]);


});
Route::get("/states",[StatesController::class,"all"]);
Route::get("/municipales/{id}",[StatesController::class,"municipales"]);
Route::post("/register",[UserController::class,"registration"]);
Route::post("/login",[UserController::class,"login"]);
Route::get("/login",[UserController::class,"login"])->name('login');
Route::get("/h",function (){
    return "hello";
});


