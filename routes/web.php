<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);


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

Route::get('/search', "App\Http\Controllers\BookController@home");

Route::get('/details', "App\Http\Controllers\BookController@ShowDetails");

Route::post('/search', "App\Http\Controllers\BookController@search");

Route::any('/add', "App\Http\Controllers\BookController@addbook");

Route::get('', "App\Http\Controllers\HomeController@home");

Route::post('', "App\Http\Controllers\HomeController@delete");

Route::any('/sort', "App\Http\Controllers\AjaxController@sort");

Route::any('/dragdrop', "App\Http\Controllers\AjaxController@dragdrop");


// Route::get('', function () {
//     return view('home');
// });