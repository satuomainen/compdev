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

use Illuminate\Http\Request;
use App\Vehicle;

// Main UI routes
Route::get('/', 'MainController@getWelcomePage');
Route::get('/home', 'MainController@getHomeView');
Route::get('/add-fillup/{vehicleId}', 'MainController@getAddFillupView');

// Authentication routes
Route::get('auth/login', 'MainController@getWelcomePage');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout'); 

// Registration routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset request and reset routes
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Vehicle API
Route::resource('api/vehicle', 'VehicleController');

// Fillup API
Route::resource('api/vehicle.fillup', 'FillupController');
Route::post('api/fillup', 'FillupController@createFillup');
