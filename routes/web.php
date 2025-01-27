<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('profile/{id}', function($id){
    return view('app.profile', ['id' => $id]);
})->name('profile')->middleware('auth');

Route::get('pacotes', function(){
    return view('app.pacotes');
})->name('pacotes')->middleware('auth');

Route::get('/comprar/{id}', function($id){
    return view('app.comprar', ['id' => $id]);
})->name('comprar')->middleware('auth');
