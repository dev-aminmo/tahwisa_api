<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FcmTokenController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TagController;
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
    Route::post('create', [PasswordResetController::class, "create"]);
});


Route::middleware('auth:api')->group(function () {

    // --------------------- USER PROFILE ROUTES ---------------------
    Route::group(['prefix' => '/user'], function () {
        Route::get("", [UserController::class, "details"]);
        Route::post("/updatepicture", [UserController::class, "updateProfilePicture"]);
        Route::post("/logout", [UserController::class, "logout"]);
    });
    // --------------------- END USER PROFILE ROUTES ---------------------


    // --------------------- REVIEWS ROUTES ---------------------

    Route::group(['prefix' => '/reviews'], function () {
        Route::get("{place}", [ReviewController::class, "index"]);
        Route::get("user/{place}", [ReviewController::class, "userReview"]);
        Route::post("post", [ReviewController::class, "postReview"]);
        Route::post("update/{review}", [ReviewController::class, "update"]);
        Route::delete("delete/{review}", [ReviewController::class, "delete"]);
    });
    // --------------------- END REVIEWS ROUTES ---------------------

    // --------------------- PLACES ROUTES ---------------------
    Route::group(['prefix' => '/places'], function () {
        Route::get("all", [PlaceController::class, "all"]);
        Route::post("add", [PlaceController::class, "addPlace"]);
        Route::put("updateinfo/{id}", [PlaceController::class, "updatePlaceInfo"]);
        Route::get("search", [PlaceController::class, "search"]);
        Route::get("autocomplete", [PlaceController::class, "autocomplete"]);
        Route::get("{place}", [PlaceController::class, "index"]);

        // --------------------- WISHES ROUTES ---------------------

        Route::group(['prefix' => '/wishes'], function () {
            Route::get("all", [WishController::class, "all"]);
            Route::post("add", [WishController::class, "add"]);
            Route::delete("delete/{place}", [WishController::class, "delete"]);
        });

        // --------------------- END WISHES ROUTES ---------------------
    });
    // --------------------- END PLACES ROUTES ---------------------

    // --------------------- TAGS ROUTES ---------------------
    Route::get("tags", [TagController::class, "tags"]);
    Route::get("tags/top", [TagController::class, "top"]);
    // --------------------- END TAGS ROUTES ---------------------

    // --------------------- FCM Tokens ROUTES ---------------------
    Route::post("fcm/tokens/add", [FcmTokenController::class, "add"]);
    // --------------------- END FCM Tokens ROUTES ---------------------
    // --------------------- Notifications ROUTES ---------------------
    Route::get("notifications/index", [NotificationController::class, "index"]);
    Route::get("notifications/read/{id}", [NotificationController::class, "read"]);
    Route::get("notifications/refused/messages/{notification}", [NotificationController::class, "getRefusePlaceMessages"]);

    // --------------------- END Notifications ROUTES ---------------------
    // --------------------- Admin ROUTES ---------------------
    Route::get("admin/place/check/{place}", [AdminController::class, "checkIfPlaceIsAvailable"]);
    Route::post("admin/place/approve/{place}", [AdminController::class, "approvePlace"]);
    Route::get("admin/place/refuse/messages", [AdminController::class, "getRefusePlaceMessages"]);
    Route::post("admin/place/refuse/{place}", [AdminController::class, "refusePlace"]);

    // --------------------- END Admin ROUTES ---------------------

});

// --------------------- STATES & MUNICIPALS ROUTES ---------------------
Route::get("/states", [StatesController::class, "all"]);
Route::get("/municipales/{id}", [StatesController::class, "municipales"]);
// ---------------------  END STATES & MUNICIPALS ROUTES ---------------------

// ---------------------  AUTH ROUTES ---------------------
Route::post("/register", [UserController::class, "registration"]);
Route::post("/login", [UserController::class, "login"]);
Route::get("/login", [UserController::class, "login"])->name('login');
Route::post('social/login', [UserController::class, 'socialLogin']);
// ---------------------  END AUTH ROUTES ---------------------
