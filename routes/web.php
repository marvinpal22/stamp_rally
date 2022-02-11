<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Store;

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

Route::get('qrcodes','QrController@downloadQr');

// Route::get('/link', function () {
// if(file_exists(public_path('storage/notification/')))
// 	echo 'true '.public_path('storage/notification/');
// else
// 	echo 'false';
// //dd(Artisan::call('make:command sup'));
// 	//dd(Artisan::call('storage:link'));
// 	//symlink('../storage/app/public', 'storage');
// });
Route::get('/files', function () {
	return view('pages.files.files');
});

Route::get('/createqrs', function () {

	Artisan::call('storage:link');

	$files = Storage::allFiles('public/qrCode');

	if(!Storage::delete($files))
		echo "Temporary files not deleted. \n";

	$stores = Store::select('id','name','store_qr_code')->get();

	foreach ($stores as $store) {
		$image = \QrCode::format('png')
				->size(200)
				->generate($store->store_qr_code);

		$basename = bin2hex(random_bytes(8));
		$filename = sprintf('%s.%0.8s', $basename, 'png');

		Storage::disk('local')->put('public/qrCode/'.$store->id.'-'.$filename, $image);

		$store->qr_image = $store->id.'-'.$filename;
		$store->update();
	}
});

Route::get('/', function () {
    return redirect('login');
});


Route::get('/login', function () {
    return view('pages.auth.login');
});

Route::get('/reset_password', function () {
    return view('pages.auth.reset_password');
});


Route::get('account', function() {
	return view('pages.admin.account');
});

Route::prefix('admin')->group(function(){
	// accounts
	Route::get('/', function() {
		return view('pages.admin.admins');
	});

	Route::get('/registration', function () {
		return view('pages.admin.register');
	});
});

Route::prefix('management')->group(function(){
	//Store routes
    Route::get('/store', function() {
		return view('pages.store.stores');
	});
	Route::get('/store/add', function() {
		return view('pages.store.storeAdd');
	});
	Route::get('/store/update/{id}', function() {
		return view('pages.store.storeUpdate');
	});

	// User routes
	Route::get('/user', function() {
		return view('pages.user.users');
	});
	Route::get('/user/add', function() {
		return view('pages.user.userAdd');
	});
	Route::get('/user/update/{id}', function() {
		return view('pages.user.userUpdate');
	});
	Route::get('/user/{id}/visited', function() {
		return view('pages.user.storeVisited');
	});
	Route::get('/user/submitted', function() {
		return view('pages.user.submitted');
	});



	//Send Notif
	// Route::get('/notification', function() {
	// 	// return view('pages.user.userUpdate');
	// 	// $stores = DB::table('stores')->get();
	// 	// return view('pages.stores',compact("stores"));
	// 	return view('pages.notification.test');
	// });

	Route::get('/notification', function() {
		return view('pages.notification.notif');
	});

	Route::get('/notifications', function() {
		return view('pages.notification.notifications');
	});

	Route::get('/notification/{id}', function() {
		return view('pages.notification.notifUpdate');
	});

	// schedule
	Route::get('/schedule', function() {
		return view('pages.schedule.schedule');
	});
	// Route::get('/test', function()
    // {
    //     $passwordReset ='asdsadas';
    //     // if (!$passwordReset)
    //     //     // echo ("Error 404, page not found.");
    //     //     return response()->json([
    //     //         'message' => 'This password reset token is invalid.'
    //     //     ], 404);
    //     // if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
    //     //     $passwordReset->delete();
    //     //     return response()->json([
    //     //         'message' => 'This password reset token is invalid.'
    //     //     ], 404);
    //     // }
    //     return redirect()->to('/reset_password')->with('token', $passwordReset);
    //     // return response()->json($passwordReset);
	// });

	// Route::get('/reset_password', function()
    // {

	// });
});


