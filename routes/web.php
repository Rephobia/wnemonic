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

Route::get('/edit/{filename}', "EditController@getEditForm");
Route::get("/add", "EditController@getAddForm");

Route::get('/', "FileController@files");
Route::get('/page/{number?}',"FileController@files");
           
Route::get('/tag/{tags}', "FileController@filsByTags");
Route::get('/tag/{tags}/page/{number?}', "FileController@filesByTags");

Route::get('/{filename}', "FileController@file");

Route::post("/add", "EditController@add");
Route::post("/edit", "EditController@edit");
Route::post("/delete", "EditController@delete");
Route::post("/cancel", "EditController@cancel");
