<?php

use App\Restroom;

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

/* Google Maps API Forward */
Route::get('/gapi', function () {
    $apiKey = env('MAPS_API_KEY');
    return file_get_contents("https://maps.googleapis.com/maps/api/js?key=$apiKey");
});

Route::get('hashe', function() {
	$salt = "domenic";
	$hashRuns = 12000000;

	$hashed = "lu";
	for ($i = 0; $i < $hashRuns; $i++) {
		$hashed = hash("sha256", $hashed.$salt);
	}

	return $hashed;
});

Route::get('/', function () {
    return view('interactive_map');
});

Route::get('/admin-login', 'AdminController@index');

Route::post('/admin', 'AdminController@admin_login');

Route::post('/admin-logout', 'AdminController@admin_logout');

Route::get('/add', function () {
    return view('add_restroom', ['restroom' => new Restroom()]);
});

Route::post('/add', 'RestroomController@add');

Route::get('restroom_list', function() {
    return view('restroom_list', ['restrooms' => Restroom::all()]);
});

Route::get('/edit/{id}', 'AdminController@edit_restroom')->name('edit');

Route::post('/edit/{id}', 'RestroomController@edit');

Route::get('/delete','AdminController@delete_restroom');
