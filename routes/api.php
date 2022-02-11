<?php

use Illuminate\Http\Request;
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
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    // Route::get('find/{token}', 'PasswordResetController@find');
    // Route::post('reset', 'PasswordResetController@reset');
});


    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
    Route::post('admin/login', 'AdminController@login');
    Route::post('refresh', 'LoginController@refresh');


// Route::middleware(['verified'])->group(function () {
Route::middleware('auth:api')->group(function () {



    Route::post('verify/qrcode', 'VerifyQrCodeController@verify');
    Route::post('logout', 'LoginController@logout');
    Route::get('posts', 'PostController@index');
	Route::get('entry', 'EntryController@index');
    Route::apiResource('stores','StoreController');
	Route::post('/store/restore/{id}','StoreController@restore');
    //
    Route::post('stores/insert','StoreController@backupstore');

    // Route::post('notification/read','NotificationController@updateEntry');
    // Route::get('notification/entries', 'NotificationController@notif');
    // Route::post('notification/send','NotificationController@sendPush');
    // Route::apiResource('notification', 'NotificationController');

    Route::patch('notification/update/{id}', 'NotificationController@update');
    Route::get('notification/entries', 'NotificationController@entries');
    Route::post('notification/send','NotificationController@sendPush');
    Route::put('notification/read','NotificationController@updateEntry');
    Route::apiResource('notification', 'NotificationController')->except(['update']);



	Route::post('changepass', 'AdminController@changePassword');
	Route::post('admin/register', 'AdminController@register');

    Route::get('user/info', 'UserController@userInfo');
    Route::put('update/user/password', 'UserController@change_password');


    Route::put('update/user/form', 'UserController@applicationForm');
    Route::put('update/user/acc', 'UserController@accountEdit');



	Route::get('user/{id}/visited', 'UserController@visited');
	Route::get('user/submitted','UserController@submitted');
	Route::apiResource('user','UserController')->except(['update']);

	Route::get('schedule/{type}','ScheduleController@show');
    Route::patch('schedule/{type}','ScheduleController@update');
    Route::get('schedule/','ScheduleController@checker');


	// Route::post('notification/save','NotificationController@saveToken');
	Route::post('notification/send','NotificationController@sendPush');
});






