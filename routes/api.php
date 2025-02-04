<?php

use App\Http\Controllers\AuthController;
use App\Models\Absence;
use App\Models\Pointage;
use App\Models\User;
use App\Models\Tuteur;
use App\Services\OneSignalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('hello',function(){
    return response()->json('hello world');
});

Route::namespace('App\Http\Controllers\Api')
   ->middleware('api')
    ->group(function () {
        Route::post('login','AuthController@login');
        //Route::post('notify','SyncController@notify');
        //Route::post('notify/all','SyncController@notifyAll');
});

Route::namespace('App\Http\Controllers\Api')
    ->middleware('api')
    ->group(function () {
        Route::post('link','SyncController@setLink');
        Route::post('notify','SyncController@notify');
        Route::post('notify/all','SyncController@notifyAll');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'tuteur',
    'namespace'=>'App\Http\Controllers\Api\Tuteur'
], function ($router) {
    Route::get('/home/{phone}','HomeController@index');
    Route::get('/eleve/{id}','HomeController@getEleveByLinkId');
    Route::post('/notify','HomeController@notify');
    Route::get('/segment','HomeController@createSegment');
});






