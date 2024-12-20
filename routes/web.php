<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->away('/mobile/index.html');
});

Route::get('/maps', [UsersController::class, 'maps']);
Route::get('/webview/kedatangan/{token}', [UsersController::class, 'webview']);
