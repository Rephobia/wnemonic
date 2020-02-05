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

Route::get('/', "FileController@showAll");
Route::get('/tag/{tags}', "TagController@show");
Route::get('/edit/{filename}', "EditController@getEditForm");
Route::get('/{filename}', "FileController@show");


Route::post("/add", "FileController@add");
Route::post("/delete", "FileController@delete");
Route::post("/rename", "FileController@rename");

Route::post("/edit", "EditController@edit");
Route::post("/cancel", "EditController@cancel");
