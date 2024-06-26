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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/files', 'FileController@index');
Route::post('/files', 'FileController@store');
Route::post('/files/upload', 'FileController@upload');
Route::delete('/files/{id}', 'FileController@logicalDelete');
Route::delete('/files/delete/{id}', 'FileController@physicalDelete');
Route::post('/files/mass-upload', 'FileController@massUpload');
