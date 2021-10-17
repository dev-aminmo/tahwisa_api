<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/403', function () {
    return response()->json([
        'message' => 'User does not have any of the necessary access rights.',
        'code' => 403
    ], 403);
});

Route::group([
    'middleware' => 'web'
], function () {

    Route::post('password/reset', [PasswordResetController::class, "reset"])->name("resetpassword");
    Route::get('password/find/{token}', [PasswordResetController::class, 'find'])->name('find');

    Route::middleware('super_admin')->group(function () {

        //  Route::get('admin/tags', [UserController::class, 'index']);
        Route::get('admin/tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('admin/tags/create', [TagController::class, 'create'])->name('tags.create');
        Route::post('admin/tags/delete', [TagController::class, 'delete'])->name('tags.delete');
        Route::get('admin/admins', [UserController::class, 'index'])->name('users.index');
        Route::get("user/delete/{id}", [UserController::class, "delete"])->name("user/delete");


    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [UserController::class, 'index'])->name('home');
//Route::get('/users', [UserController::class, 'index'])->name('users.index');
