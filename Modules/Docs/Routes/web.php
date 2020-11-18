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
    Route::get('/home', ['as' => 'docs.home',   'uses' => 'DocsController@index']);


    Route::group(['prefix' => 'documento'], function() {
        Route::get('',['as' => 'docs.documento',   'uses' => 'DocumentoController@index']);
    });

    Route::group(['prefix' => 'documentoExterno'], function() {
        Route::get('',['as' => 'docs.documentoExterno',   'uses' => 'DocumentoController@index']);
    });

    Route::group(['prefix' => 'controle_registro'], function() {
        Route::get('',['as' => 'docs.controle_registro',   'uses' => 'WorkflowController@index']);
    });

    Route::group(['prefix' => 'setor'], function() {
        Route::get('',['as' => 'docs.setor',   'uses' => 'SetorController@index']);
    });

    Route::group(['prefix' => 'configuracao'], function() {
        Route::get('',['as' => 'docs.configuracao',   'uses' => 'SetorController@index']);
    });

    Route::group(['prefix' => 'workflow'], function() {
        Route::get('',['as' => 'docs.workflow',   'uses' => 'WorkflowController@index']);
    });





});

