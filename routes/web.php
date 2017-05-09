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

Route::get('/', 'DashboardController@home')->name('home');
Route::get('/dashboard', 'DashboardController@index');
Auth::routes();
Route::get('/diplomas/professor/list', 'DiplomaController@diplomaListProfessor');
Route::get('/diplomas/student/list', 'DiplomaController@diplomasListStudent');
Route::resource('/diplomas', 'DiplomaController');
Route::get('/student/groups', 'GroupController@student_groups');
Route::get('/professor/groups', 'GroupController@professor_groups');
Route::get('/translation/professor/diplomas/list', 'TranslationController@professor_diplomas_list');
Route::get('/translation/student/diplomas/list', 'TranslationController@student_diplomas_list');
Route::post('/diplomas/requests/{id}', 'DiplomaRequestController@store');
