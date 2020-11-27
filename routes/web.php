<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/core/home');
})->name('home');

Route::get('login',  ['uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['uses' => 'Auth\LoginController@login'])->name('login');
Route::post('logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');

// LOG-OUT
Route::get('logout', function () {
   Auth::logout();
   Session::flush();
   return Redirect::to('login');
});