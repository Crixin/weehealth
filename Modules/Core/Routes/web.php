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

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('core')->group(function () {

        Route::get('/', ['uses' => 'HomeCoreController@index']);
        Route::get('home', ['uses' => 'HomeCoreController@index'])->name('core.home');

        /*
        * Empresa
        */
        Route::group(['prefix' => 'empresa'], function () {
            
            Route::get('',                          ['as' => 'core.empresa',                        'uses' => 'EmpresaController@index']);
            Route::get('nova',                      ['as' => 'core.empresa.nova',                   'uses' => 'EmpresaController@newEnterprise']);
            Route::post('salvar',                   ['as' => 'core.empresa.salvar',                 'uses' => 'EmpresaController@saveEnterprise']);
            Route::get('editar/{id}',               ['as' => 'core.empresa.editar',                 'uses' => 'EmpresaController@editEnterprise']);
            Route::post('alterar',                  ['as' => 'core.empresa.alterar',                'uses' => 'EmpresaController@updateEnterprise']);
            Route::post('deletar',                  ['as' => 'core.empresa.deletar',                'uses' => 'AjaxController@deleteEnterprise']);
        });

        /*
        * PERFIL
        */
        Route::group(['prefix' => 'perfil'], function () {
            Route::get('',              ['as' => 'core.perfil',         'uses' => 'PerfilController@index']);
            Route::get('novo',          ['as' => 'core.perfil.novo',    'uses' => 'PerfilController@create']);
            Route::post('salvar',       ['as' => 'core.perfil.salvar',  'uses' => 'PerfilController@store']);
            Route::get('editar/{id}',   ['as' => 'core.perfil.editar',  'uses' => 'PerfilController@edit']);
            Route::post('alterar/{id}', ['as' => 'core.perfil.alterar', 'uses' => 'PerfilController@update']);
            Route::post('deletar',      ['as' => 'core.perfil.deletar', 'uses' => 'PerfilController@destroy']);
        });

        

        /*
        * USUÁRIO
        */
        Route::group(['prefix' => 'usuario'], function () {
            Route::get('',                  ['as' => 'core.usuario',                 'uses' => 'UsuarioController@index']);
            Route::get('editar/{id}',       ['as' => 'core.usuario.editar',          'uses' => 'UsuarioController@editUser'])->middleware('blockAdmin');
            Route::post('alterar',          ['as' => 'core.usuario.alterar',         'uses' => 'UsuarioController@updateUser']);
            Route::post('alterar-senha',    ['as' => 'core.usuario.alterarSenha',    'uses' => 'UsuarioController@updateUserPassword']);
            Route::post('deletar',          ['as' => 'core.usuario.deletar',         'uses' => 'AjaxController@deleteUser'])->middleware('blockAdmin');
            Route::get('register',          ['as' => 'core.usuario.register',        'uses' => 'Auth\RegisterController@showRegistrationForm']);
            Route::post('save',             ['as' => 'core.usuario.save',            'uses' => 'Auth\RegisterController@register']);
        
        });


        /*
        * SETOR
        */
        Route::group(['prefix' => 'setor'], function () {
            Route::get('',              ['as' => 'core.setor',         'uses' => 'SetorController@index']);
            Route::get('novo',          ['as' => 'core.setor.novo',    'uses' => 'SetorController@create']);
            Route::post('salvar',       ['as' => 'core.setor.salvar',  'uses' => 'SetorController@store']);
            Route::get('editar/{id}',   ['as' => 'core.setor.editar',  'uses' => 'SetorController@edit']);
            Route::post('alterar',      ['as' => 'core.setor.alterar', 'uses' => 'SetorController@update']);
            Route::post('deletar',      ['as' => 'core.setor.deletar', 'uses' => 'SetorController@destroy']);
            Route::get('usuarios-vinculados/{id}',  ['as' => 'core.setor.usuariosVinculados',    'uses' => 'SetorController@linkedUsers']);
            Route::post('vincular-usuarios',        ['as' => 'core.setor.vincularUsuarios',      'uses' => 'SetorController@updateLinkedUsers']);
        });

        
        /*
        * NOTIFICAÇãO
        */
        Route::group(['prefix' => 'notificacao'], function () {
            Route::get('',                          ['as' => 'core.notificacao',                           'uses' => 'NotificacaoController@index']);
            Route::get('marcar-todas-como-lidas',   ['as' => 'core.notificacao.marcar-todas-como-lidas',   'uses' => 'NotificacaoController@markAllAsRead']);
        });
        
        /*
        * CONFIGURAÇÃO
        */
        Route::group(['prefix' => 'configuracao'], function () {
            Route::get('parametros',        ['as' => 'core.configuracao.parametros',         'uses' => 'ConfiguracaoController@indexParameters'])->middleware('onlyAllowSuperAdmins');
            Route::get('administradores',   ['as' => 'core.configuracao.administradores',    'uses' => 'ConfiguracaoController@indexAdministrators'])->middleware('onlyAllowSuperAdmins');
        
            Route::group(['prefix' => 'setup'], function () {
                Route::get('',         ['as' => 'core.configuracao.setup.index',   'uses' => 'SetupController@index']);
                Route::post('alterar', ['as' => 'core.configuracao.setup.alterar', 'uses' => 'SetupController@update']);
            });
        });

        /*
        * GRUPOS
        */
        Route::group(['prefix' => 'grupo', 'as' => 'core.'], function () {
            Route::get('',                          ['as' => 'grupo',                       'uses' => 'GrupoController@index']);
            Route::get('novo',                      ['as' => 'grupo.novo',                  'uses' => 'GrupoController@newGroup']);
            Route::post('salvar',                   ['as' => 'grupo.salvar',                'uses' => 'GrupoController@saveGroup']);
            Route::get('editar/{id}',               ['as' => 'grupo.editar',                'uses' => 'GrupoController@editGroup']);
            Route::post('alterar',                  ['as' => 'grupo.alterar',               'uses' => 'GrupoController@updateGroup']);
            Route::get('usuarios-vinculados/{id}',  ['as' => 'grupo.usuariosVinculados',    'uses' => 'GrupoController@linkedUsers']);
            Route::post('vincular-usuarios',        ['as' => 'grupo.vincularUsuarios',      'uses' => 'GrupoController@updateLinkedUsers']);
            Route::post('deletar',	                ['as' => 'grupo.deletar',               'uses' => 'AjaxController@deleteGroup']);
        });

        Route::group(['prefix' => 'atualizar'], function () {
            Route::post('parametro',                ['as' => 'core.atualizar.parametro',                   'uses' => 'AjaxController@updateParamValue']);
            Route::post('parametro-ativo',          ['as' => 'core.atualizar.parametroAtivo',              'uses' => 'AjaxController@updateParamActiveValue']);
            Route::post('permissao-usuario',        ['as' => 'core.atualizar.permissaoUsuario',            'uses' => 'AjaxController@updateUserPermissions']);
            
            Route::post('permissao-administrador',  ['as' => 'core.atualizar.permissaoAdministrador',      'uses' => 'AjaxController@updateAdministratorPermissions'])->middleware('onlyAllowSuperAdmins');
            Route::post('setup',                    ['as' => 'core.atualizar.setup',                       'uses' => 'AjaxController@updateSetup']);
        });
    });
});