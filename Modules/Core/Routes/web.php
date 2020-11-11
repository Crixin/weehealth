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

Route::prefix('core')->group(function () {

    Route::get('login',  ['uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('login', ['uses' => 'Auth\LoginController@login'])->name('core.login');
    Route::post('logout', ['uses' => 'Auth\LoginController@logout'])->name('core.logout');

    // LOG-OUT
    Route::get('logout', function () {
        Auth::logout();
        Session::flush();
        return Redirect::to('/core/login');
    });

    Route::get('home', ['uses' => 'HomeCoreController@index'])->name('core.home');

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
    * USUÁRIO
    */
    Route::group(['prefix' => 'usuario', 'middleware' => 'permissionamento:administrador'], function () {
        Route::get('',                  ['as' => 'usuario',                 'uses' => 'UsuarioController@index']);
        Route::get('editar/{id}',       ['as' => 'usuario.editar',          'uses' => 'UsuarioController@editUser'])->middleware('blockAdmin');
        Route::post('alterar',          ['as' => 'usuario.alterar',         'uses' => 'UsuarioController@updateUser']);
        Route::post('alterar-senha',    ['as' => 'usuario.alterarSenha',    'uses' => 'UsuarioController@updateUserPassword']);
        Route::post('deletar',          ['as' => 'usuario.deletar',         'uses' => 'AjaxController@deleteUser'])->middleware('blockAdmin');
        Route::get('register',          ['as' => 'usuario.register',        'uses' => 'Auth\RegisterController@showRegistrationForm']);
        Route::post('save',             ['as' => 'usuario.save',            'uses' => 'Auth\RegisterController@register']);
    
    });

    
    /*
    * NOTIFICAÇãO
    */
    Route::group(['prefix' => 'notificacao'], function () {
        Route::get('',                          ['as' => 'notificacao',                           'uses' => 'NotificacaoController@index']);
        Route::get('marcar-todas-como-lidas',   ['as' => 'notificacao.marcar-todas-como-lidas',   'uses' => 'NotificacaoController@markAllAsRead']);
    });
    
    /*
    * CONFIGURAÇÃO
    */
    Route::group(['prefix' => 'configuracao', 'as' => 'configuracao.'], function () {
        Route::get('parametros',        ['as' => 'parametros',         'uses' => 'ConfiguracaoController@indexParameters'])->middleware('onlyAllowSuperAdmins');
        Route::get('administradores',   ['as' => 'administradores',    'uses' => 'ConfiguracaoController@indexAdministrators'])->middleware('onlyAllowSuperAdmins');
    
        Route::group(['prefix' => 'setup', 'as' => 'setup.', 'middleware' => 'permissionamento:conf_setup'], function () {
            Route::get('',         ['as' => 'index',   'uses' => 'SetupController@index']);
            Route::post('alterar', ['as' => 'alterar', 'uses' => 'SetupController@update']);
        });
    });

    Route::group(['prefix' => 'atualizar', 'as' => 'atualizar.'], function () {
        Route::post('parametro',                ['as' => 'parametro',                   'uses' => 'AjaxController@updateParamValue']);
        Route::post('parametro-ativo',          ['as' => 'parametroAtivo',              'uses' => 'AjaxController@updateParamActiveValue']);
        Route::post('permissao-usuario',        ['as' => 'permissaoUsuario',            'uses' => 'AjaxController@updateUserPermissions']);
        
        Route::post('permissao-administrador',  ['as' => 'permissaoAdministrador',      'uses' => 'AjaxController@updateAdministratorPermissions'])->middleware('onlyAllowSuperAdmins');
        Route::post('setup',                    ['as' => 'setup',                       'uses' => 'AjaxController@updateSetup']);
    });
});
