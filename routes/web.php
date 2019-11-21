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

Route::get('/', "WebController@book_list");
Route::get('/qrocde', "WebController@qrcode");
Route::get('/chapter/list', "WebController@chapter_list");
Route::get('/book/list', "WebController@book_list");
Route::get('/book/detail', "WebController@book_detail");
Route::get('/book/getMore', "WebController@getMore");
Route::get('/book/share', "WebController@book_share");
Route::get('/spread/index', "WebController@spread");
Route::get('/downUrl', "WebController@downUrl");
