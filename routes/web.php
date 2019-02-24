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
    return view('tasks');
})->name('no_user')->middleware('guest');;
//Route::get('/', 'LoadPagesController@index_tasks')->middleware('guest');

Route::get('/tasks', 'LoadPagesController@index_tasks')->name('tasks')->middleware('auth');
Route::get('/tasks/{nav}', 'LoadPagesController@folder_tasks')->middleware('auth');


Route::post('/tasks/add', 'TaskController@task_add');
Route::post('/tasks/image/upload', 'ImageController@image_upload')->name('image.upload');
Route::post('/navs/image/delete', 'ImageController@image_delete');
Route::post('/tasks/{task}/image/delete', 'TaskController@task_image_delete');
Route::delete('/tasks/{task}', 'TaskController@delete');
Route::post('/tasks/{task}/done', 'TaskController@done');
Route::post('/tasks/{task}/notdone', 'TaskController@not_done');
Route::post('/tasks/{task}/edit', 'TaskController@edit');

Route::post('/nav', 'NavController@nav_index');
Route::post('/avatar/upload', 'NavController@ava_upload');
Route::post('/avatar/delete', 'NavController@ava_delete');
Route::post('/name_edit', 'NavController@name_edit');
Route::post('/password_edit', 'NavController@password_edit');
Route::post('/nav/main/edit', 'NavController@nav_main_edit');
Route::post('/nav/{nav}/edit', 'NavController@nav_edit');
Route::post('/nav/add', 'NavController@nav_add');
Route::delete('/nav/{nav}', 'NavController@nav_delete');

Route::post('/setting/angle_edit', 'SettingController@angle_edit');
Route::post('/setting/distance_edit', 'SettingController@distance_edit');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');



