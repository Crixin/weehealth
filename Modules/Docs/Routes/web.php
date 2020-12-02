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

    Route::get('/', 'DocsController@index')->name('docs.home');
    Route::get('/home', ['as' => 'docs.home',   'uses' => 'DocsController@index']);


    Route::group(['prefix' => 'configuracao'], function() {
        Route::get('',['as' => 'docs.configuracao',   'uses' => 'SetorController@index']);
    });

    /*
    * PLANO
    */
    Route::group(['prefix' => 'plano' , 'as' => 'docs.'], function () {
        Route::get('',              ['as' => 'plano',         'uses' => 'PlanoController@index']);
        Route::get('novo',          ['as' => 'plano.novo',    'uses' => 'PlanoController@create']);
        Route::post('salvar',       ['as' => 'plano.salvar',  'uses' => 'PlanoController@store']);
        Route::get('editar/{id}',   ['as' => 'plano.editar',  'uses' => 'PlanoController@edit']);
        Route::post('alterar',      ['as' => 'plano.alterar', 'uses' => 'PlanoController@update']);
        Route::post('deletar',      ['as' => 'plano.deletar', 'uses' => 'PlanoController@destroy']);
    });

    /*
    * TIPO DOCUMENTO
    */
    Route::group(['prefix' => 'tipo-documento' , 'as' => 'docs.'], function () {
        Route::get('',              ['as' => 'tipo-documento',         'uses' => 'TipoDocumentoController@index']);
        Route::get('novo',          ['as' => 'tipo-documento.novo',    'uses' => 'TipoDocumentoController@create']);
        Route::post('salvar',       ['as' => 'tipo-documento.salvar',  'uses' => 'TipoDocumentoController@store']);
        Route::get('editar/{id}',   ['as' => 'tipo-documento.editar',  'uses' => 'TipoDocumentoController@edit']);
        Route::post('alterar',      ['as' => 'tipo-documento.alterar', 'uses' => 'TipoDocumentoController@update']);
        Route::post('deletar',      ['as' => 'tipo-documento.deletar', 'uses' => 'TipoDocumentoController@destroy']);
    });

    /*
    * FLUXO
    */
    Route::group(['prefix' => 'fluxo' , 'as' => 'docs.'], function () {
        Route::get('',              ['as' => 'fluxo',         'uses' => 'FluxoController@index']);
        Route::get('novo',          ['as' => 'fluxo.novo',    'uses' => 'FluxoController@create']);
        Route::post('salvar',       ['as' => 'fluxo.salvar',  'uses' => 'FluxoController@store']);
        Route::get('editar/{id}',   ['as' => 'fluxo.editar',  'uses' => 'FluxoController@edit']);
        Route::post('alterar',      ['as' => 'fluxo.alterar', 'uses' => 'FluxoController@update']);
        Route::post('deletar',      ['as' => 'fluxo.deletar', 'uses' => 'FluxoController@destroy']);

        Route::get('{id}/etapa',    ['as' => 'fluxo.etapa',    'uses' => 'EtapaFluxoController@index']);

    });




    Route::group(['prefix' => 'documento'], function() {
        Route::get('',['as' => 'docs.documento',   'uses' => 'DocumentoController@index']);
    });

    Route::group(['prefix' => 'documentoExterno'], function() {
        Route::get('',['as' => 'docs.documentoExterno',   'uses' => 'DocumentoController@index']);
    });

    Route::group(['prefix' => 'controle_registro'], function() {
        Route::get('',['as' => 'docs.controle_registro',   'uses' => 'WorkflowController@index']);
    });

    

    Route::group(['prefix' => 'workflow'], function() {
        Route::get('',['as' => 'docs.workflow',   'uses' => 'WorkflowController@index']);
    });





});

