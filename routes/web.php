<?php

use Illuminate\Http\Request;

use App\Restroom;
use App\Http\Controllers\AdminController;

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

/* TEMPORARY/DEVELOPMENT ROUTES */
Route::get('restroom-list', function() {
    return view('restroom_list', ['restrooms' => Restroom::all()]);
});

Route::get('hash-admin-pwd', function(Request $request) {
    return AdminController::hashPassword($request->p);
});

/* Google Maps API Forward */
Route::get('/gapi', function () {
    $apiKey = env('MAPS_API_KEY');
    return file_get_contents("https://maps.googleapis.com/maps/api/js?key=$apiKey");
});

/* Home */
Route::get('/', function () {
    return view('interactive_map');
});

/* Show admin login form */
Route::get('/admin-login', function () {
    return view('admin_login');
});

/* Process admin login attempt */
Route::post('/admin-login', 'AdminController@login');

/* Show 'Add Restroom' form */
Route::get('/add-restroom', function () {
    return view('add_restroom', ['restroom' => new Restroom()]);
});

/* Process 'Add Restroom' attempt */
Route::post('/add-restroom', 'RestroomController@add');

/* Query the database by Geolocation and return list (JSON array) of nearby
restrooms */
Route::get('search-query-geo', 'RestroomController@searchByGeoPos');

/* *ADMIN* Log out the admin */
Route::get('/admin-logout', function () {
    /* Clear the session and go back to home */
    Session::flush();
    Session::flash("flash_success", "Logged out.");
    return redirect('/');
});

/* *ADMIN* Show 'Search Restroom's form */
Route::get('/admin-search', function () {
    if (Session::has('admin')) {
        return view('admin_search');
    } else {
        Session::flash('flash_not_admin', "Unauthorised: Must be an Administrator to search for a restroom");
        return redirect('/');
    }
});

/* *ADMIN* Query the database and return JSON result (AJAX) */
Route::get('/search-query', 'RestroomController@search');

/* *ADMIN* Delete a restroom */
Route::get('/delete-restroom/{id}', 'RestroomController@delete');

/* *ADMIN* Show 'Edit Restroom' form */
Route::get('/edit/{id}', function (Request $request) {
    if (Session::has('admin')) {
        $restroom = Restroom::find($request->id);
        return view('edit_restroom')->with('restroom', $restroom);
    } else {
        Session::flash('flash_not_admin', "Unauthorised: Must be an Administrator to edit a restroom");
        return redirect('/');
    }
});

/* *ADMIN* Process 'Edit Restroom' attempt */
Route::post('/edit/{id}', 'RestroomController@edit');
