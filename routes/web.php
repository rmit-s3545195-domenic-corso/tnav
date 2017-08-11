<?php

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
    return ('interactive map view here');
});

Route::get('/admin_login', 'AdminController@index');

Route::get('/add', function () {
    return view('add_restroom');
});

Route::get('/edit', 'AdminController@edit_restroom');
