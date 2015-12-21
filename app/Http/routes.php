<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'StockDataController@index');

Route::group(['middleware' => 'auth'], function()
{
	Route::get('home', 'HomeController@index');

	Route::get('settings', 'HomeController@settings');
	
	Route::post('settings', 'HomeController@saveSettings');

	Route::get('stockdata', 'StockDataController@index');
	Route::get('stockdata/create', 'StockDataController@create');
	Route::post('stockdata/create', 'StockDataController@store');
	Route::get('stockdata/remove/{id}', 'StockDataController@remove');
	Route::get('stockdata/{id}/{format?}', 'StockDataController@show');

	Route::post('preferences/{key}', 'PreferenceController@update');
});

Route::group(['middleware' => 'admin'], function() 
{
	Route::resource('users', 'UserController');

	Route::get('users/{id}/login', 'UserController@login');
	Route::get('usage', 'UsageController@index');
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
