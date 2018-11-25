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

Route::get('/', 'FrontendController@welcome');

Route::get('/token', 'TokenController@index');

Route::get('/wichtelgroup/create', 'RegistrationController@create');
Route::get('/wichtelgroup/{group}', 'FrontendController@showWichtelgroup')->name('wichtelgroup');
