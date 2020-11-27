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

Route::prefix('portal')->group(function () {

    Route::get('/', 'PortalController@index')->name('portal.home');
    Route::get('/home', ['as' => 'portal.home',   'uses' => 'PortalController@index']);

    /*
    * DOWNLOAD DE DOSSIES
    */
    Route::get('download/dossie/{token}',   ['as' => 'portal.download.dossie.verify',   'uses' => 'DossieDocumentosController@verifyLink']);
    Route::post('download/dossie/download', ['as' => 'portal.download.dossie.download', 'uses' => 'DossieDocumentosController@downloadByLink']);

    Route::group(['middleware' => ['auth']], function () {

        /*
        * EMPRESAS
        */
        Route::group(['prefix' => 'empresa', 'as' => 'portal.'], function () {
            
            Route::get('',                          ['as' => 'empresa',                        'uses' => 'EmpresaPortalController@index']);
            
            Route::get('usuarios-vinculados/{id}',  ['as' => 'empresa.usuariosVinculados',     'uses' => 'EmpresaUserController@create']);
            Route::post('vincular-usuarios',        ['as' => 'empresa.vincularUsuarios',       'uses' => 'EmpresaUserController@update']);
            Route::post('empresa/usuario',          ['as' => 'relacao.empresaUsuario.deletar', 'uses' => 'AjaxController@deleteLinkEnterpriseUser']);

            Route::get('grupos-vinculados/{id}',    ['as' => 'empresa.gruposVinculados',       'uses' => 'EmpresaGrupoController@create']);
            Route::post('vincular-grupos',          ['as' => 'empresa.vincularGrupos',         'uses' => 'EmpresaGrupoController@update']);
            Route::post('empresa/grupo',            ['as' => 'relacao.empresaGrupo.deletar',   'uses' => 'AjaxController@deleteLinkEnterpriseGroup']);

            Route::get('processos-vinculados/{id}', ['as' => 'empresa.processosVinculados',    'uses' => 'EmpresaProcessoController@create']);
            Route::post('vincular-processos',       ['as' => 'empresa.vincularProcessos',      'uses' => 'EmpresaProcessoController@update']);
            Route::post('empresa/processo',         ['as' => 'relacao.empresaProcesso.deletar','uses' => 'AjaxController@deleteLinkEnterpriseProcess']);
        });
            
        
        
        Route::group(['prefix' => 'ged', 'as' => 'portal.ged.'], function () {
            Route::get('getRegistro',          ['as' => 'getRegistro',          'uses' => 'AjaxController@getRegistro']);
            Route::get('getDocumento',         ['as' => 'getDocumento',         'uses' => 'AjaxController@getDocumento']);
            Route::get('buscaInfoArea',        ['as' => 'buscaInfoArea',        'uses' => 'AjaxController@buscaInfoArea']);
            Route::post('pesquisaRegistro',    ['as' => 'pesquisaRegistro',     'uses' => 'AjaxController@pesquisaRegistro']);
            Route::post('postDocumento',       ['as' => 'postDocumento',        'uses' => 'AjaxController@postDocumento']);
            Route::get('getIndicesComumAreas', ['as' => 'getIndicesComumAreas', 'uses' => 'AjaxController@getIndicesComumAreas']);
            Route::get('getPrestadores',       ['as' => 'getPrestadores',       'uses' => 'AjaxController@getPrestadores']);

            Route::get('',              ['as' => '',           'uses' => 'GedController@index']);
            Route::get('novo',          ['as' => 'novo',       'uses' => 'GedController@create']);
            Route::post('salvar',       ['as' => 'salvar',     'uses' => 'GedController@store']);
            Route::get('editar',        ['as' => 'editar',     'uses' => 'GedController@edit']);
            Route::post('alterar',      ['as' => 'alterar',    'uses' => 'GedController@update']);
            Route::get('upload',        ['as' => 'upload',     'uses' => 'GedController@upload']);
            Route::post('search',       ['as' => 'search',     'uses' => 'GedController@search']);
            Route::get('search-view',   ['as' => 'search-view','uses' => 'GedController@searchView']);
            //Route::get('search-documents/{id}', ['as' => 'search-documents', 'uses' => 'GedController@searchDocuments']);
            Route::get('list-document/{empresaProcesso}/{registro}', ['as' => 'list-document', 'uses' => 'GedController@listDocument']);
            Route::get('access-document/{empresaProcesso}/{documento}', ['as' => 'access-document', 'uses' => 'GedController@accessDocument']);
            Route::post('create-document', ['as' => 'create-document', 'uses' => 'GedController@createDocuments']);
        });
        
        /*
        * Requisições AJAX
        */
        Route::group(['prefix' => 'deletar', 'as' => 'portal.deletar.'], function () {
            Route::post('documento',            ['as' => 'documento',                   'uses' => 'AjaxController@deleteDocument']);
            Route::post('tarefa',               ['as' => 'tarefa',                      'uses' => 'TarefaController@deleteTarefa']);
            Route::post('registro',             ['as' => 'registro',                    'uses' => 'AjaxController@deleteRegister']);
        });
        
        
        Route::group(['prefix' => 'buscar', 'as' => 'portal.buscar.'], function () {
            Route::get('processByEnterpriseAndProcesso', ['as' => 'processByEnterpriseAndProcess', 'uses' => 'AjaxController@getProcessByEnterpriseAndProcesso']);
        });


        Route::group(['prefix' => 'atualizar', 'as' => 'portal.atualizar.'], function () {
            
            Route::post('empresa/grupo',            ['as' => 'relacao.empresaGrupo',        'uses' => 'AjaxController@updateLinkEnterpriseGroup']);
            Route::post('empresa/usuario',          ['as' => 'relacao.empresaUsuario',      'uses' => 'AjaxController@updateLinkEnterpriseUser']);
        });
        
        Route::group(['prefix' => 'ajax', 'as' => 'portal.ajax.'], function () {
            Route::post('resendDossie', ['as' => 'resendDossie', 'uses' => 'AjaxController@resendDossie']);
        });

        /*
        * DOSSIÊ DOCUMENTOS
        */
        Route::group(['prefix' => 'dossieDocumentos','as' => 'portal.dossieDocumentos.'], function () {
            Route::get('',          ['as' => 'novo',            'uses' => 'DossieDocumentosController@novo']);
            Route::get('list',      ['as' => 'list',            'uses' => 'DossieDocumentosController@list']);
            Route::post('download', ['as' => 'download',        'uses' => 'DossieDocumentosController@downloadDossie']);
            Route::post('deletar',  ['as' => 'dossie.deletar',  'uses' => 'AjaxController@deleteDossie']);
        });


        /*
        * EDIÇÂO DOCUMENTOS
        */
        Route::group(['prefix' => 'edicaoDocumento', 'as' => 'portal.edicaoDocumento.'], function () {
            Route::get('',                      ['as' => 'index',                   'uses' => 'EdicaoDocumentoController@index']);
            Route::post('deleteRegistroAndDoc', ['as' => 'deleteRegistroAndDoc',    'uses' => 'EdicaoDocumentoController@deleteRegistroAndDoc']);
        });

        /*
        * PROCESSOS
        */
        Route::group(['prefix' => 'processo', 'as' => 'portal.'], function () {
            Route::get('',              ['as' => 'processo',            'uses' => 'ProcessoController@index']);
            Route::get('novo',          ['as' => 'processo.novo',       'uses' => 'ProcessoController@newProcess']);
            Route::post('salvar',       ['as' => 'processo.salvar',     'uses' => 'ProcessoController@saveProcess']);
            Route::get('editar/{id}',   ['as' => 'processo.editar',     'uses' => 'ProcessoController@editProcess']);
            Route::post('alterar',      ['as' => 'processo.alterar',    'uses' => 'ProcessoController@updateProcess']);
            Route::post('deletar',      ['as' => 'processo.deletar',    'uses' => 'AjaxController@deleteProcess']);
        });

        /**
         * PROCESSO - documentos
         */
        Route::group(['prefix' => 'processo', 'as' => 'portal.processo.', 'middleware' => 'userCanByEntreprise'], function () {
             Route::get('buscar/{idEmpresa}/{idProcesso}',  ['as' => 'buscar',             'uses' => 'ProcessoController@search']);
             Route::post('listarRegistros',                 ['as' => 'listarRegistros',    'uses' => 'ProcessoController@listRegisters']);
             Route::get('listarDocumentos/{_idRegistro}',   ['as' => 'listarDocumentos',   'uses' => 'ProcessoController@listDocuments']);
             Route::get('documento/{_idDocumento}',         ['as' => 'acessarDocumento',   'uses' => 'ProcessoController@accessDocument']);
             Route::post('documento/aprovar',               ['as' => 'documento.aprovar',  'uses' => 'ProcessoController@approveDocument']);
             Route::post('documento/rejeitar',              ['as' => 'documento.rejeitar', 'uses' => 'ProcessoController@rejectDocument']);
             Route::post('documento/update',                ['as' => 'documento.update',   'uses' => 'ProcessoController@updateDocument']);
             Route::get('upload/{idEmpresa}/{idProcesso}',  ['as' => 'upload',             'uses' => 'ProcessoController@upload']);
             Route::post('realizarUpload',                  ['as' => 'realizarUpload',     'uses' => 'ProcessoController@makeUpload']);
         });


        /*
        * RELATÓRIOS
        */
        Route::group(['prefix' => 'relatorio', 'as' => 'portal.relatorio.'], function () {
            //Route::get('conferencia/{idEmpresa}/{idProcesso}',   ['as' => 'conferencia',  'uses' => 'RelatorioController@index']);
            //Route::post('conferencia/busca',                     ['as' => 'buscar',       'uses' => 'RelatorioController@search']);
            Route::get('documentos/',                  ['as' => 'documentos',  'uses' => 'RelatorioDocumentosController@index']);
            Route::post('documentos/gerar',            ['as' => 'documentos.gerar',  'uses' => 'RelatorioDocumentosController@gerar']);
        });


       
        /*
        * DASHBOARDS
        */
        Route::group(['prefix' => 'dashboards', 'as' => 'portal.'], function () {
            Route::get('',                          ['as' => 'dashboards',                    'uses' => 'DashboardController@index']);
            Route::get('novo',                      ['as' => 'dashboards.criar',              'uses' => 'DashboardController@newDashboard']);
            Route::post('salvar',                   ['as' => 'dashboards.salvar',             'uses' => 'DashboardController@saveDashboard']);
            Route::get('editar/{id}',               ['as' => 'dashboards.editar',             'uses' => 'DashboardController@editDashboard']);
            Route::post('alterar',                  ['as' => 'dashboards.alterar',            'uses' => 'DashboardController@updateDashboard']);
            Route::get('usuarios-vinculados/{id}',  ['as' => 'dashboards.usuariosVinculados', 'uses' => 'DashboardController@linkedUsers']);
            Route::post('vincular-usuarios',        ['as' => 'dashboards.vincularUsuarios',   'uses' => 'DashboardController@updateLinkedUsers']);
            Route::post('buscaDashboard',           ['as' => 'dashboards.buscaDashboard',     'uses' => 'DashboardController@findDashboard']);
            Route::post('deletar',                  ['as' => 'dashboards.deletar',            'uses' => 'DashboardController@deleteDashboard']);
        });
        
        /*
        * DASHBOARD VIEW
        */
        Route::group(['prefix' => 'dashboard', 'as' => 'portal.'], function () {
            Route::get('view/{id}', ['as' => 'dashboard.view', 'uses' => 'DashboardController@view']);
        });


        /*
        * CONFIG. TAREFA
        */
        Route::group(['prefix' => 'config-tarefa', 'as' => 'portal.'], function () {
            Route::get('',                          ['as' => 'config-tarefa',                    'uses' => 'ConfiguracaoTarefaController@index']);
            Route::get('nova',                      ['as' => 'config-tarefa.criar',              'uses' => 'ConfiguracaoTarefaController@newConfiguracaoTarefa']);
            Route::post('salvar',                   ['as' => 'config-tarefa.salvar',             'uses' => 'ConfiguracaoTarefaController@saveConfiguracaoTarefa']);
            Route::get('editar/{id}',               ['as' => 'config-tarefa.editar',             'uses' => 'ConfiguracaoTarefaController@editConfiguracaoTarefa']);
            Route::post('alterar',                  ['as' => 'config-tarefa.alterar',            'uses' => 'ConfiguracaoTarefaController@updateConfiguracaoTarefa']);
            Route::post('deletar',                  ['as' => 'config-tarefa.deletar',            'uses' => 'ConfiguracaoTarefaController@deleteConfiguracaoTarefa']);
        });

        /*
        * TAREFA
        */
        Route::group(['prefix' => 'tarefa', 'as' => 'portal.'], function () {
            Route::get('',                          ['as' => 'tarefa',                    'uses' => 'TarefaController@index']);
            Route::get('nova',                      ['as' => 'tarefa.criar',              'uses' => 'TarefaController@newTarefa']);
            Route::post('salvar',                   ['as' => 'tarefa.salvar',             'uses' => 'TarefaController@saveTarefa']);
            Route::get('editar/{id}',               ['as' => 'tarefa.editar',             'uses' => 'TarefaController@editTarefa']);
            Route::post('alterar',                  ['as' => 'tarefa.alterar',            'uses' => 'TarefaController@updateTarefa']);
        });


        /*
        * EMPRESA PROCESSO GRUPOS
        */
        Route::group(['prefix' => 'empresa-processo-grupo'], function () {
            Route::get('novo/{empresaProcesso}',    ['as' => 'empresa-processo-grupo.criar',                    'uses' => 'EmpresaProcessoGrupoController@create']);
            Route::post('salvar',                   ['as' => 'empresa-processo-grupo.salvar',                   'uses' => 'EmpresaProcessoGrupoController@store']);
            Route::post('alterar',                  ['as' => 'empresa-processo-grupo.alterar',                  'uses' => 'EmpresaProcessoGrupoController@update']);
            Route::get('get/pre-filtro-processo',   ['as' => 'empresa-processo-grupo.get.pre-filtro-processo',  'uses' => 'AjaxController@getPreFiltroProcessos']);
            Route::post('deletar',                  ['as' => 'empresa-processo-grupo.deletar',                  'uses' => 'AjaxController@deleteLinkEmpresaProcessoGrupo']);
        });
    });
});
