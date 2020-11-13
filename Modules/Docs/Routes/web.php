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
// @codingStandardsIgnoreFile
use Illuminate\Support\Facades\Route;

Route::prefix('docs')->group(function () {

    Route::get('/', 'DocsController@index')->name('home');
    Route::get('/home', 'DocsController@index')->name('home');
});

