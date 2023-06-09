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

Route::post('/api-key/generate', 'App\Http\Controllers\ApikeyController@generate_api_key');
Route::get('/example/test', 'App\Http\Controllers\ExampleController@test_method')->middleware('validateapikey');

