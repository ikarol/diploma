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
Route::get('/diplomas/student/groups', 'GroupController@student_groups');
Route::get('/diplomas/professor/groups', 'GroupController@professor_groups');
Route::get('/translation/professor/diplomas/list', 'TranslationController@professor_diplomas_list');
Route::get('/translation/student/diplomas/list', 'TranslationController@student_diplomas_list');
Route::post('/diplomas/requests/{id}', 'DiplomaRequestController@store');
Route::patch('/diplomas/requests/{id}', 'DiplomaRequestController@resend');
Route::delete('/diplomas/{id}/requests', 'DiplomaRequestController@delete');
Route::get('/translation/professor/requests/list', 'TranslationController@professor_requests_list');
Route::get('/diplomas/professor/requests', 'DiplomaRequestController@professor_requests_list');
Route::patch('/diplomas/professor/requests/decline/{student}/{request}', 'DiplomaRequestController@decline');
Route::patch('/diplomas/professor/requests/accept/{student}/{request}', 'DiplomaRequestController@accept');
Route::get('/diplomas/professor/requests/groups', 'GroupController@professor_request_groups');
Route::get('/diplomas/student/requests/groups', 'GroupController@student_request_groups');
Route::get('/diplomas/jobs/{id}', 'DiplomaJobController@show');
Route::get('/translation/professor/diplomas/jobs', 'TranslationController@professor_diploma_jobs');
Route::post('/diplomas/jobs/{id}', 'DiplomaJobController@store');
Route::delete('/diplomas/jobs/{id}', 'DiplomaJobController@delete');
Route::patch('/diplomas/jobs/{id}', 'DiplomaJobController@update');
