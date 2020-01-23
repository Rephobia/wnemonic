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

// Route::get('/', function () {
    // return view("main");
// });


Route::get('/', "FileController@show_all");

Route::get('/search', function ()
{
    return view("search");
});

Route::get('/{filename}', "FileController@show");


Route::post("/", "FileController@add");


// Route::get('/testing/{id}',[
     // 'uses' => 'TestController@testMethod', 
     // 'as' => 'test.route'
// ]);
