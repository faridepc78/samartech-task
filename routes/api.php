<?php

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

Route::group(['prefix' => 'v1/dev', 'as' => 'api.v1.dev.', 'middleware' => ['api', 'throttle:50,1'],
    'namespace' => 'App\Http\Controllers\Api\V1\Dev'], function () {

    Route::apiResource('posts', 'PostController');

});
