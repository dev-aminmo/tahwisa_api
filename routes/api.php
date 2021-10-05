<?php

use App\Http\Controllers\TagController;
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

    // --------------------- REVIEWS ROUTES ---------------------

    Route::group(['prefix'=>'/reviews'],function(){
        Route::get("{place}",[ReviewController::class,"index"]);
        Route::get("user/{place}",[ReviewController::class,"userReview"]);
        Route::post("post",[ReviewController::class,"postReview"]);
        Route::post("update/{review}",[ReviewController::class,"update"]);
        Route::delete("delete/{review}",[ReviewController::class,"delete"]);
    });

    // --------------------- END REVIEWS ROUTES ---------------------

    Route::group(['prefix'=>'/place'],function(){
        Route::get("all",[PlaceController::class,"all"]);
        Route::post("add",[PlaceController::class,"addPlace"]);
        Route::put("updateinfo/{id}",[PlaceController::class,"updatePlaceInfo"]);
        Route::get("search",[PlaceController::class,"search"]);
        Route::get("autocomplete",[PlaceController::class,"autocomplete"]);
       // Route::get("{id}",[PlaceController::class,"get"]);
        Route::get("{place}",[PlaceController::class,"index"]);


        // Route::put("updatepictures/{id}",[PlaceController::class,"updatePlacePictures"]);

    });
    Route::post("/wish/add",[WishController::class,"add"]);
    Route::get("/wishes",[WishController::class,"all"]);
    Route::delete("/wish/delete/{place}",[WishController::class,"delete"]);
Route::get("tags",[TagController::class,"tags"]);
Route::get("tags/top",[TagController::class,"top"]);

});
Route::get("/states",[StatesController::class,"all"]);
Route::get("/municipales/{id}",[StatesController::class,"municipales"]);

Route::post("/register",[UserController::class,"registration"]);
Route::post("/login",[UserController::class,"login"]);
Route::get("/login",[UserController::class,"login"])->name('login');
Route::get("/h",function (){
    return "hello";
});
Route::post('social/login', [UserController::class, 'socialLogin']);


