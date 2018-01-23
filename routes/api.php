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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::get('posts', 'ApiController@index');
Route::get('post/{id}', 'ApiController@getBlog');
Route::delete('delete-post/{id}', 'ApiController@deleteBlog');
Route::post('save-post', 'ApiController@saveBlog');

Route::post('save-user', 'ApiController@saveUser');
Route::post('update-user', 'ApiController@updateUser');
Route::post('forgot-password', 'ApiController@forgotPassword');
Route::post('change-password', 'ApiController@changePassword');
Route::post('login-user', 'ApiController@loginUser');
Route::get('logout-user', 'ApiController@logoutUser');
Route::get('all-users', 'ApiController@allUsers');
