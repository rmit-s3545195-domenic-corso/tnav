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

Route::get('/', function () {
    return view('current_location');
});

Route::get('/admin_login', 'AdminController@index');

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
