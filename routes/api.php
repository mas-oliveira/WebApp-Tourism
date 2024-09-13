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

//Route::resource('cliente', 'App\Http\Controllers\ClienteController');
Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::get('me', 'App\Http\Controllers\AuthController@me');
    Route::get('myUserId', 'App\Http\Controllers\AuthController@myUserId'); 
    Route::get('myUserImage', 'App\Http\Controllers\AuthController@myUserImage'); 
    Route::get('myName', 'App\Http\Controllers\AuthController@myName'); 
    Route::get('getUserInfo/{id}', 'App\Http\Controllers\AuthController@getUserInfo');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('changeProfilePic', 'App\Http\Controllers\AuthController@changeProfilePic');
    Route::apiResource('publicacao', 'App\Http\Controllers\PublicacaoController');
    Route::apiResource('pacote', 'App\Http\Controllers\PacoteController');
    Route::apiResource('compra', 'App\Http\Controllers\CompraController');
    Route::post('addComentario', 'App\Http\Controllers\PublicacaoController@addComment');
    Route::post('removeComment', 'App\Http\Controllers\PublicacaoController@removeComment');
    Route::get('getComments', 'App\Http\Controllers\PublicacaoController@getComments');
    Route::post('addLike', 'App\Http\Controllers\PublicacaoController@addLike');
    Route::post('removeLike', 'App\Http\Controllers\PublicacaoController@removeLike');
    Route::post('changePacoteStatus', 'App\Http\Controllers\PacoteController@changePacoteStatus');
    Route::get('getPacotesFeed/{estado}', 'App\Http\Controllers\PacoteController@getPacotesFeed');
    Route::get('getPacotesPorUsuario/{user_id}', 'App\Http\Controllers\PacoteController@getPacotesPorUsuario');
    Route::get('getPacotesCompradosPorUsuario/{user_id}', 'App\Http\Controllers\CompraController@getPacotesCompradosPorUsuario');
});


//Route::prefix('v1')->group(function() {
  
//});

Route::post('login', 'App\Http\Controllers\AuthController@login');


