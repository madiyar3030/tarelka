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
	Route::post('add_goal', 'AdminController@AddGoal')->name('AddGoal');
	Route::get('/delete_goal/{id}', 'AdminController@DeleteGoal')->name('DeleteGoal');
    Route::get('/edit_goal/{id}', 'AdminController@EditGoal')->name('EditGoal');
	Route::post('save_goal', 'AdminController@SaveGoal')->name('SaveGoal');

	Route::get('/meals', 'AdminController@Meals')->name('Meals');
	Route::post('add_meal', 'AdminController@AddMeal')->name('AddMeal');
	Route::get('/delete_meal/{id}', 'AdminController@DeleteMeal')->name('DeleteMeal');
    Route::get('/edit_meal/{id}', 'AdminController@EditMeal')->name('EditMeal');
	Route::post('save_meal', 'AdminController@SaveMeal')->name('SaveMeal');

	Route::get('/quiz', 'AdminController@Quiz')->name('Quiz');

	Route::get('/tasks', 'AdminController@Tasks')->name('Tasks');
});