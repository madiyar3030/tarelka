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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('auth', 'ApiController@Auth');
Route::post('check', 'ApiController@CheckCode');
Route::get('meals', 'ApiController@Meals');
Route::get('goals', 'ApiController@Goals');
Route::get('tasks', 'ApiController@Tasks');
Route::post('profile', 'ApiController@Profile');
