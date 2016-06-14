<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('/login', 'HomeController@login');

Route::get('/dashboard', 'AdminController@index');
Route::get('/files', 'AdminController@files');

Route::get('/search', 'AdminController@search');

Route::get('/upload', 'AdminController@upload');
Route::post('/upload', 'AdminController@doUpload');

Route::get('/delete/{id}', 'AdminController@delete');

Route::get('/logout', 'AdminController@logout');
