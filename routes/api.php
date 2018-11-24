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

Route::get('/', function (Request $request) {
    return [
        'api' => [
            'version' => '0.2.0',
            'framework' => 'Laravel 5.7',
        ],
        'author' => [
            'name' => 'Hans-Helge Bürger',
            'email' => 'santa@wichtel.me',
        ],
    ];
});

Route::group(['prefix' => 'v1'], function () {
    Route::resource('/wichtelgroups', 'WichtelGroupController', ['except' => [
        'create', 'edit'
    ]]);

    Route::resource('/wichtelgroups/{group}/wichtelmembers', 'WichtelMemberController', ['except' => [
        'create', 'edit'
    ]]);

    Route::resource('/users', 'UserController', ['except' => [
        'create', 'edit'
    ]]);
});

