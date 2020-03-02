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


use App\Http\Controllers\
{SelectController, EditController};

Route::get("/edit/{filename}",           [EditController::class, "getEditForm"]);
Route::get("/add",                       [EditController::class, "getAddForm"]);

Route::get("/",                          [SelectController::class, "files"]);
Route::get("/{filename}",                [SelectController::class, "file"]);
Route::get("/page/{number?}",            [SelectController::class, "files"]);

Route::get("/search/{tags}",                [SelectController::class, "search"]);
Route::get("/search/{tags}/page/{number?}", [SelectController::class, "search"]);

Route::post("/add",    [EditController::class, "add"]);
Route::post("/edit",   [EditController::class, "edit"]);
Route::post("/delete", [EditController::class, "delete"]);
Route::post("/cancel", [EditController::class, "cancel"]);

Route::post("/search", [SelectController::class, "redirectToSearch"]);
