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

Route::get('/disciplines/list/', 'DisciplineController@list');

Route::get('/translation/professor/diplomas/list', 'TranslationController@professor_diplomas_list');
Route::get('/translation/student/diplomas/list', 'TranslationController@student_diplomas_list');
Route::get('/translation/professor/requests/list', 'TranslationController@professor_requests_list');
Route::get('/translation/professor/diplomas/jobs', 'TranslationController@professor_diploma_jobs');

Route::get('/diplomas/student/groups', 'GroupController@student_groups');
Route::get('/diplomas/professor/groups', 'GroupController@professor_groups');
Route::get('/diplomas/professor/requests/groups', 'GroupController@professor_request_groups');
Route::get('/diplomas/student/requests/groups', 'GroupController@student_request_groups');

Route::resource('/diplomas', 'DiplomaController');
Route::get('/diplomas/professor/list', 'DiplomaController@diplomaListProfessor');
Route::get('/diplomas/student/list', 'DiplomaController@diplomasListStudent');

Route::post('/diplomas/requests/{id}', 'DiplomaRequestController@store');
Route::patch('/diplomas/requests/{id}', 'DiplomaRequestController@resend');
Route::delete('/diplomas/{id}/requests', 'DiplomaRequestController@delete');
Route::get('/diplomas/professor/requests', 'DiplomaRequestController@professor_requests_list');
Route::patch('/diplomas/professor/requests/decline/{student}/{request}', 'DiplomaRequestController@decline');
Route::patch('/diplomas/professor/requests/accept/{student}/{request}', 'DiplomaRequestController@accept');

Route::get('/diplomas/jobs/{id}', 'DiplomaJobController@show');
Route::post('/diplomas/jobs/{id}', 'DiplomaJobController@store');
Route::delete('/diplomas/jobs/{id}', 'DiplomaJobController@delete');
Route::patch('/diplomas/jobs/{id}', 'DiplomaJobController@update');


Route::get('/translation/professor/course_projects/list', 'TranslationController@professor_courseProjects_list');
Route::get('/translation/student/course_projects/list', 'TranslationController@student_courseProjects_list');

Route::resource('/course_projects', 'CourseProjectController');
Route::get('/course_projects/professor/list', 'CourseProjectController@courseProjectListProfessor');
Route::get('/course_projects/student/list', 'CourseProjectController@courseProjectListStudent');

Route::post('/course_projects/requests/{id}', 'CourseProjectRequestController@store');
Route::patch('/course_projects/requests/{id}', 'CourseProjectRequestController@resend');
Route::delete('/course_projects/{id}/requests', 'CourseProjectRequestController@delete');
Route::get('/course_projects/professor/requests', 'CourseProjectRequestController@professor_requests_list');
Route::patch('/course_projects/professor/requests/decline/{student}/{request}', 'CourseProjectRequestController@decline');
Route::patch('/course_projects/professor/requests/accept/{student}/{request}', 'CourseProjectRequestController@accept');

Route::get('/course_projects/jobs/{id}', 'CourseProjectJobController@show');
Route::post('/course_projects/jobs/{id}', 'CourseProjectJobController@store');
Route::delete('/course_projects/jobs/{id}', 'CourseProjectJobController@delete');
Route::patch('/course_projects/jobs/{id}', 'CourseProjectJobController@update');

Route::get('/admin/', 'AdminController@index');
Route::get('/admin/groups/', 'AdminController@groups');
Route::post('/admin/groups', 'GroupController@store');
Route::patch('/admin/groups/{id}', 'GroupController@update');
Route::get('/admin/disciplines', 'AdminController@disciplines');
Route::post('/admin/disciplines', 'DisciplineController@store');
Route::patch('/admin/disciplines/{id}', 'DisciplineController@update');

Route::get('/translation/admin/groups', 'TranslationController@admin_groups');
Route::get('/translation/admin/disciplines', 'TranslationController@admin_disciplines');
