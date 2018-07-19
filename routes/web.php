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
	Route::get('/info', 'AdminController@Info')->name('Info');
	Route::post('/save_info', 'AdminController@SaveInfo')->name('SaveInfo');
	Route::get('/chat/{id}', 'AdminController@Chat')->name('Chat');
	Route::post('send_message', 'AdminController@SendMessage')->name('SendMessage');

	Route::get('/clients', 'AdminController@Clients')->name('Clients');
	Route::get('/delete_client/{id}', 'AdminController@DeleteClient')->name('DeleteClient');
	Route::get('/upgrade/{id}', 'AdminController@Upgrade')->name('Upgrade');
	Route::get('/downgrade/{id}', 'AdminController@Downgrade')->name('Downgrade');

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
	Route::post('add_quiz', 'AdminController@AddQuiz')->name('AddQuiz');
	Route::get('/delete_quiz/{id}', 'AdminController@DeleteQuiz')->name('DeleteQuiz');
	Route::get('/edit_quiz/{id}', 'AdminController@EditQuiz')->name('EditQuiz');
	Route::post('save_quiz', 'AdminController@SaveQuiz')->name('SaveQuiz');

	Route::get('/question/{id}', 'AdminController@Question')->name('Question');
	Route::post('add_question', 'AdminController@AddQuestion')->name('AddQuestion');
	Route::get('/delete_question/{id}', 'AdminController@DeleteQuestion')->name('DeleteQuestion');
	Route::get('/edit_question/{id}', 'AdminController@EditQuestion')->name('EditQuestion');
	Route::post('save_question', 'AdminController@SaveQuestion')->name('SaveQuestion');

	Route::get('/tasks', 'AdminController@Tasks')->name('Tasks');
	Route::post('add_task', 'AdminController@AddTask')->name('AddTask');
	Route::get('/delete_task/{id}', 'AdminController@DeleteTask')->name('DeleteTask');
	Route::get('/edit_task/{id}', 'AdminController@EditTask')->name('EditTask');
	Route::post('save_task', 'AdminController@SaveTask')->name('SaveTask');
});