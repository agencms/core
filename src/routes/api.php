<?php

namespace Silvanite\Agencms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

Route::prefix('agencms')
     ->namespace('Silvanite\Agencms\Controllers')
     ->middleware(['api', 'cors', 'auth:api'])
     ->group(function() {
        Route::get('config', 'ConfigController@index');
     });