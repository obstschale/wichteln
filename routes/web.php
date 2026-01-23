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

Route::get('/   ', 'FrontendController@welcome');
Route::get('/imprint', 'FrontendController@imprint');
Route::get('/privacy', 'FrontendController@dataPrivacy');

Route::get('/answer', 'TokenController@index');
Route::post('/answer/confirm', 'TokenController@confirm')->name('token.confirm');

Route::get('/wichtelgroup/create', 'RegistrationController@create');
Route::get('/wichtelgroup/{group}', 'FrontendController@showWichtelgroup')->name('wichtelgroup');

Route::middleware(['throttle:5,1', \App\Http\Middleware\AdminAuth::class])->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin.dashboard');
    Route::post('/admin', 'AdminController@index')->name('admin.login');
});

Route::get('/admin/logout', function () {
    session()->forget('admin_authenticated');

    return redirect('/');
})->name('admin.logout');
