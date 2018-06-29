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
    return view('login');
})->name('Login');
Route::post('auth', 'AdminController@Auth')->name('Auth');
Route::get('/logout', 'AdminController@Logout')->name('Logout');
Route::group(['middleware' => ['admin']], function () {
	Route::get('/index', 'AdminController@Index')->name('Index');
	Route::get('/clients', 'AdminController@Clients')->name('Clients');
	Route::get('/goals', 'AdminController@Goals')->name('Goals');
	Route::get('/meals', 'AdminController@Meals')->name('Meals');
	Route::get('/clients', 'AdminController@Clients')->name('Clients');
	Route::get('/quiz', 'AdminController@Quiz')->name('Quiz');
	Route::get('/tasks', 'AdminController@Tasks')->name('Tasks');
});