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

Route::middleware('verify.token')->group(function () {
    Route::get('history/{token}', 'UsersController@history')->name('history');
    Route::post('timein/{token}', 'UsersController@timein')->name('timein');
    Route::get('profile/{token}', 'UsersController@profile')->name('profile');
    Route::get('report/download/{token}', 'UsersController@reportDownload')->name('report.download');
    Route::post('upload/image', 'UsersController@uploadPicture')->name('upload.image');
    Route::get('places', 'UsersController@places')->name('places');
    Route::get('logout/{token}', 'UsersController@logout')->name('logout');
    Route::get('report/{token}', 'ReportController@index')->name('report');
    Route::get('kedatangan/{token}', 'ReportController@attendance')->name('attendance');
});
