<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', 'UsersController@login');
Route::get('history/{token}', 'UsersController@history');
Route::post('timein/{token}', 'UsersController@timein');
Route::get('profile/{token}', 'UsersController@profile');
Route::get('report/download/{token}', 'UsersController@reportDownload');
Route::post('upload/image', 'UsersController@uploadPicture');
Route::get('places', 'UsersController@places');
Route::get('logout/{token}', 'UsersController@logout');
