<?php

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


Route::group(['prefix' => 'v1'], static function () {
    Route::resource('/wichtelgroups', 'WichtelGroupController', ['except' => [
        'create', 'edit', 'index'
    ]]);

    Route::resource('/wichtelgroups/{group}/wichtelmembers', 'WichtelMemberController', ['except' => [
        'create', 'edit'
    ]]);

    Route::resource('/users', 'UserController', ['except' => [
        'index', 'create', 'edit', 'destroy', 'update'
    ]]);
});

