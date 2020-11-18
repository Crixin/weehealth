<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/core/home');
})->name('home');

/* Route::group(['prefix' => 'core', 'as' => 'core.'], function () {
   Route::get('portal', function () {
      return Redirect::to('/portal/home');
   })->name('portal');

   Route::get('docs', function () {
      return Redirect::to('/docs/home');
   })->name('docs');

   Route::get('forms', function () {
      return Redirect::to('/forms/home');
   })->name('forms');

}); */
