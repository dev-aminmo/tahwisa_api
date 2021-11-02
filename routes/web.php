<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

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
Route::get('/note', function () {

    $SERVER_API_KEY = env('FCM_SERVER_API_KEY', '');
    $data = [
        "registration_ids" =>
            FcmToken::pluck('token')->toArray(),
        "notification" => [

            "title" => 'Aw chtaho 3',

            "body" => 'Description',

            "sound" => "default" // required for sound on ios

        ],

    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    dd($response);

});
Route::get('/fcm', function () {
    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60 * 20);
    $notificationBuilder = new PayloadNotificationBuilder('sahbi title');
    $notificationBuilder->setBody('Hello world')
        ->setSound('default');
    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['a_data' => 'my_data']);
    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();
    $tokens = FcmToken::pluck('token')->toArray();
    $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
    foreach ($downstreamResponse->tokensToDelete() as $token) {
        DB::transaction(function () use ($token) {
            FcmToken::where('token', $token)->delete();
            return 'success';
        });
    }
    dd($downstreamResponse);
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
        Route::post('admin/tags/edit', [TagController::class, 'edit'])->name('tags.edit');
        Route::get('admin/admins', [UserController::class, 'index'])->name('users.index');
        Route::post('admin/user/edit', [UserController::class, 'adminUpdateUser'])->name('users.edit');


    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [UserController::class, 'index'])->name('home');
//Route::get('/users', [UserController::class, 'index'])->name('users.index');
