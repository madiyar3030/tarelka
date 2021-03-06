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

Route::post('auth', 'ApiController@Auth');
Route::post('profile', 'ApiController@Profile');
Route::get('info', 'ApiController@Info');

Route::get('meals', 'ApiController@Meals');
Route::post('send_meal', 'ApiController@SendMeal');

Route::get('goals', 'ApiController@Goals');
Route::post('send_goal', 'ApiController@SendGoal');

Route::post('tasks', 'ApiController@Tasks');

Route::post('chat', 'ApiController@Chat');
Route::post('send_message', 'ApiController@SendMessage');
Route::post('delete_message', 'ApiController@DeleteMessage');
Route::post('hide_message', 'ApiController@HideMessage');

Route::post('get_progress','ApiController@GetProgress');
Route::post('list_quiz','ApiController@ListQuiz');
Route::post('list_question','ApiController@ListQuestion');
Route::post('send_result','ApiController@SendResult');