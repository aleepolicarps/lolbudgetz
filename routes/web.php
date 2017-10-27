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
    return view('welcome');
});

Route::get('signup', function() {
    return view('signup');
})->name('signup');

Route::get('complete-signup/{uuid}', 'Auth\RegisterController@show_complete_signup')->name('complete_signup');
Route::get('successful-signup', function() {
    return view('successful_signup');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
