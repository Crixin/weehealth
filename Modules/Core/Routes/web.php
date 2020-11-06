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
use Modules\Core\Http\Controllers\Auth\LoginController;
use Modules\Core\Http\Controllers\HomeCoreController;

Route::prefix('core')->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm']);
    Route::post('login', [LoginController::class,'login'])->name('core.login');
    Route::post('logout', [LoginController::class,'logout'])->name('core.logout');

    Route::get('home', [HomeCoreController::class, 'index'])->name('core.home');

    /*
    * Empresa
    */
    Route::group(['prefix' => 'empresa', 'middleware' => 'permissionamento:mod_base'], function () {
        Route::get('',                          ['as' => 'empresa',                        'uses' => 'EmpresaController@index']);
        Route::get('nova',                      ['as' => 'empresa.nova',                   'uses' => 'EmpresaController@newEnterprise']);
        Route::post('salvar',                   ['as' => 'empresa.salvar',                 'uses' => 'EmpresaController@saveEnterprise']);
        Route::get('editar/{id}',               ['as' => 'empresa.editar',                 'uses' => 'EmpresaController@editEnterprise']);
        Route::post('alterar',                  ['as' => 'empresa.alterar',                'uses' => 'EmpresaController@updateEnterprise']);
        Route::post('deletar',                  ['as' => 'empresa.deletar',                'uses' => 'AjaxController@deleteEnterprise']);
    });

    /*
    * PERFIL
    */
    Route::group(['prefix' => 'perfil', 'middleware' => 'permissionamento:administrador'], function () {
        Route::get('',              ['as' => 'perfil',         'uses' => 'PerfilController@index']);
        Route::get('novo',          ['as' => 'perfil.novo',    'uses' => 'PerfilController@create']);
        Route::post('salvar',       ['as' => 'perfil.salvar',  'uses' => 'PerfilController@store']);
        Route::get('editar/{id}',   ['as' => 'perfil.editar',  'uses' => 'PerfilController@edit']);
        Route::post('alterar/{id}', ['as' => 'perfil.alterar', 'uses' => 'PerfilController@update']);
        Route::post('deletar',      ['as' => 'perfil.deletar', 'uses' => 'PerfilController@destroy']);
    });

    /*
    * PROCESSOS
    */
    Route::group(['prefix' => 'processo', 'middleware' => 'permissionamento:mod_base'], function () {
        Route::get('',              ['as' => 'processo',            'uses' => 'ProcessoController@index']);
        Route::get('novo',          ['as' => 'processo.novo',       'uses' => 'ProcessoController@newProcess']);
        Route::post('salvar',       ['as' => 'processo.salvar',     'uses' => 'ProcessoController@saveProcess']);
        Route::get('editar/{id}',   ['as' => 'processo.editar',     'uses' => 'ProcessoController@editProcess']);
        Route::post('alterar',      ['as' => 'processo.alterar',    'uses' => 'ProcessoController@updateProcess']);
        Route::post('deletar',      ['as' => 'processo.deletar',    'uses' => 'AjaxController@deleteProcess']);
    });

    /*
    * USUÁRIO
    */
    Route::group(['prefix' => 'usuario', 'middleware' => 'permissionamento:administrador'], function () {
        Route::get('',                  ['as' => 'usuario',                 'uses' => 'UsuarioController@index']);
        Route::get('editar/{id}',       ['as' => 'usuario.editar',          'uses' => 'UsuarioController@editUser'])->middleware('blockAdmin');
        Route::post('alterar',          ['as' => 'usuario.alterar',         'uses' => 'UsuarioController@updateUser']);
        Route::post('alterar-senha',    ['as' => 'usuario.alterarSenha',    'uses' => 'UsuarioController@updateUserPassword']);
        Route::post('deletar',          ['as' => 'usuario.deletar',         'uses' => 'AjaxController@deleteUser'])->middleware('blockAdmin');;
    });

    /*
    * NOTIFICAÇãO
    */
    Route::group(['prefix' => 'notificacao'], function () {
        Route::get('',                          ['as' => 'notificacao',                           'uses' => 'NotificacaoController@index']);
        Route::get('marcar-todas-como-lidas',   ['as' => 'notificacao.marcar-todas-como-lidas',   'uses' => 'NotificacaoController@markAllAsRead']);
    });


});