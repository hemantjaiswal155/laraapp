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


Route::group(['middleware' => 'prevent-back-history'], function(){
    /* Index route */
    Route::get('/', function () {
        return view('welcome');
    });

    /* Authentication routes */
    Auth::routes();

    /*Route to email verification*/
    Route::get('email-verification/{token}', 'Auth\RegisterController@verifyEmail');

    /* Home page route */
    Route::get('/home', 'HomeController@index')->name('home');


    /* Customer route */
    Route::resource('customer', 'CustomerController');

    /* Check duplicate email route */
    Route::get('check-email/{id?}', 'CustomerController@checkEmail');

    /*Route to get states*/
    Route::post('get-states', 'CustomerController@getStates');

    /*User route*/
    Route::resource('user', 'UserController');

    /*Country route*/
    Route::resource('country', 'CountryController');

    /*State route*/
    Route::resource('state', 'StateController');

    /*Facebook, Linkedin and twitter auth route*/
    Route::get('auth/{provider}', 'UserController@redirectToProvider');
    Route::get('auth/{provider}/login', 'UserController@handleProviderCallback');

    /*Route to check duplicate name*/
    Route::get('check-duplicate-name/{id?}', 'CountryController@checkDuplicateName');

});


Route::get('/privacy', function () {
    return 'This is privacy policy page';
});

Route::get('/terms-condition', function () {
    return 'This is terms and condition page';
});
