<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/core/home');
})->name('home');

Route::get('a', function () {
   return Redirect::to('/portal/home');
})->name('a');

Route::get('b', function () {
   return Redirect::to('/docs/home');
})->name('b');

Route::get('c', function () {
   return Redirect::to('/forms/home');
})->name('c');