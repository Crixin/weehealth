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
Route::group(['middleware' => ['auth', 'changeUser']], function () {
    Route::prefix('docs')->group(function () {

        Route::get('/', 'DocsController@index');
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
            Route::post('etapa-fluxo',  ['as' => 'tipo-documento.etapa-fluxo', 'uses' => 'TipoDocumentoController@getEtapaFluxo']);
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

            /*
            * ETAPA
            */
            Route::get('{fluxo_id}/etapa-fluxo',    ['as' => 'fluxo.etapa-fluxo',              'uses' => 'EtapaFluxoController@index']);
            Route::get('{fluxo_id}/novo',          ['as' => 'fluxo.etapa-fluxo.novo',    'uses' => 'EtapaFluxoController@create']);
            Route::post('{fluxo_id}/salvar',       ['as' => 'fluxo.etapa-fluxo.salvar',  'uses' => 'EtapaFluxoController@store']);
            Route::get('{fluxo_id}/editar/{id}',   ['as' => 'fluxo.etapa-fluxo.editar',  'uses' => 'EtapaFluxoController@edit']);
            Route::post('{fluxo_id}/alterar',      ['as' => 'fluxo.etapa-fluxo.alterar', 'uses' => 'EtapaFluxoController@update']);
            Route::post('{fluxo_id}/deletar',      ['as' => 'fluxo.etapa-fluxo.deletar', 'uses' => 'EtapaFluxoController@destroy']);
        });


        /*
        * NORMA
        */
        Route::group(['prefix' => 'norma' , 'as' => 'docs.'], function () {
            Route::get('',              ['as' => 'norma',         'uses' => 'NormaController@index']);
            Route::get('novo',          ['as' => 'norma.novo',    'uses' => 'NormaController@create']);
            Route::post('salvar',       ['as' => 'norma.salvar',  'uses' => 'NormaController@store']);
            Route::get('editar/{id}',   ['as' => 'norma.editar',  'uses' => 'NormaController@edit']);
            Route::post('alterar',      ['as' => 'norma.alterar', 'uses' => 'NormaController@update']);
            Route::post('deletar',      ['as' => 'norma.deletar', 'uses' => 'NormaController@destroy']);

            /*
            * ITEM NORMA
            */
            Route::get('{norma_id}/item-norma',    ['as' => 'norma.item-norma',         'uses' => 'ItemNormaController@index']);
            Route::get('{norma_id}/novo',          ['as' => 'norma.item-norma.novo',    'uses' => 'ItemNormaController@create']);
            Route::post('{norma_id}/salvar',       ['as' => 'norma.item-norma.salvar',  'uses' => 'ItemNormaController@store']);
            Route::get('{norma_id}/editar/{id}',   ['as' => 'norma.item-norma.editar',  'uses' => 'ItemNormaController@edit']);
            Route::post('{norma_id}/alterar',      ['as' => 'norma.item-norma.alterar', 'uses' => 'ItemNormaController@update']);
            Route::post('{norma_id}/deletar',      ['as' => 'norma.item-norma.deletar', 'uses' => 'ItemNormaController@destroy']);

            /*
            * CHECK LIST ITEM NORMA
            */
            Route::get('{norma_id}/item-norma/{item_norma_id}/check-list',    ['as' => 'norma.item-norma.check-list',         'uses' => 'CheckListItemNormaController@index']);
            Route::get('{norma_id}/item-norma/{item_norma_id}/novo',          ['as' => 'norma.item-norma.check-list.novo',    'uses' => 'CheckListItemNormaController@create']);
            Route::post('{norma_id}/item-norma/{item_norma_id}/salvar',       ['as' => 'norma.item-norma.check-list.salvar',  'uses' => 'CheckListItemNormaController@store']);
            Route::get('{norma_id}/item-norma/{item_norma_id}/editar/{id}',   ['as' => 'norma.item-norma.check-list.editar',  'uses' => 'CheckListItemNormaController@edit']);
            Route::post('{norma_id}/item-norma/{item_norma_id}/alterar',      ['as' => 'norma.item-norma.check-list.alterar', 'uses' => 'CheckListItemNormaController@update']);
            Route::post('{norma_id}/item-norma/{item_norma_id}/deletar',      ['as' => 'norma.item-norma.check-list.deletar', 'uses' => 'CheckListItemNormaController@destroy']);
            
        });


        Route::group(['prefix' => 'documento','as' => 'docs.'], function() {
            Route::any('',                    ['as' => 'documento',         'uses' => 'DocumentoController@index']);
            Route::get('novo',                ['as' => 'documento.novo',    'uses' => 'DocumentoController@create']);
            Route::post('salvar',             ['as' => 'documento.salvar',  'uses' => 'DocumentoController@store']);
            Route::get('editar/{id}',         ['as' => 'documento.editar',  'uses' => 'DocumentoController@edit']);
            Route::post('alterar',            ['as' => 'documento.alterar', 'uses' => 'DocumentoController@update']);
            Route::post('deletar',            ['as' => 'documento.deletar', 'uses' => 'DocumentoController@destroy']);
            Route::post('importar-documento', ['as' => 'documento.importar-documento', 'uses' => 'DocumentoController@importarDocumento']);
            Route::post('criar-documento',    ['as' => 'documento.criar-documento', 'uses' => 'DocumentoController@criarDocumento']);
            Route::post('documento-pai-por-tipo', ['as' => 'documento.documento-pai-por-tipo', 'uses' => 'DocumentoController@buscaDocumentoPaiPorTipo']);
            Route::post('iniciar-validacao',  ['as' => 'documento.iniciar-validacao', 'uses' => 'DocumentoController@iniciarValidacao']);
            Route::post('iniciar-revisao',    ['as' => 'documento.iniciar-revisao', 'uses' => 'DocumentoController@iniciarRevisao']);
            Route::post('obsoleto',           ['as' => 'documento.obsoleto', 'uses' => 'DocumentoController@tornarObsoleto']);
            Route::get('{id}/lista-presenca', ['as' => 'documento.lista-presenca',	'uses' => 'DocumentoController@listaPresenca']);
            Route::get('{id}/imprimir/{tipo}',['as' => 'documento.imprimir',	'uses' => 'DocumentoController@imprimir']);
            Route::get('visualizar/{id}',     ['as' => 'documento.visualizar',  'uses' => 'DocumentoController@visualizar']);
            
            Route::get('proxima-etapa',    ['as' => 'documento.proxima-etapa', 'uses' => 'DocumentoController@proximaEtapa']);
            Route::post('documento-por-tipo', ['as' => 'documento.documento-por-tipo', 'uses' => 'DocumentoController@buscaDocumentoPorTipo']);
            Route::post('documento-por-grupo', ['as' => 'documento.documento-por-grupo', 'uses' => 'DocumentoController@buscaDocumentoPorGrupo']);
        });

        /**ANEXO */
        Route::group(['prefix' => 'anexo','as' => 'docs.'], function() {
            Route::post('',         ['as' => 'anexo',    'uses' => 'AnexoDocumentoController@index']);
            Route::post('salvar',   ['as' => 'anexo.salvar',  'uses' => 'AnexoDocumentoController@store']);
            Route::post('deletar',  ['as' => 'anexo.deletar', 'uses' => 'AnexoDocumentoController@destroy']);
        });

        /**WORKFLOW */
        Route::group(['prefix' => 'workflow', 'as' => 'docs.workflow.'], function() {
            Route::post('avancar-etapa',   ['as' => 'avancar-etapa',  'uses' => 'WorkflowController@avancarEtapa']);
            Route::post('aprovar', ['as' => 'aprovar',  'uses' => 'WorkflowController@aprovar']);
        });

        Route::group(['prefix' => 'user-etapa-documento','as' => 'docs.'], function() {
            Route::post('aprovadores',      ['as' => 'user-etapa-documento.aprovadores', 'uses' => 'UserEtapaDocumentoController@aprovadores']);
        });


        Route::group(['prefix' => 'documento-externo', 'as' => 'docs.'], function() {
            Route::get('',              ['as' => 'documento-externo',         'uses' => 'DocumentoExternoController@index']);
            Route::get('novo',          ['as' => 'documento-externo.novo',    'uses' => 'DocumentoExternoController@create']);
            Route::post('salvar',       ['as' => 'documento-externo.salvar',  'uses' => 'DocumentoExternoController@store']);
            Route::get('editar/{id}',   ['as' => 'documento-externo.editar',  'uses' => 'DocumentoExternoController@edit']);
            Route::post('alterar',      ['as' => 'documento-externo.alterar', 'uses' => 'DocumentoExternoController@update']);
            Route::post('deletar',      ['as' => 'documento-externo.deletar', 'uses' => 'DocumentoExternoController@destroy']);
        });

        Route::group(['prefix' => 'controle-registro', 'as' => 'docs.'], function() {
            Route::get('',              ['as' => 'controle-registro',         'uses' => 'ControleRegistroController@index']);
            Route::get('novo',          ['as' => 'controle-registro.novo',    'uses' => 'ControleRegistroController@create']);
            Route::post('salvar',       ['as' => 'controle-registro.salvar',  'uses' => 'ControleRegistroController@store']);
            Route::get('editar/{id}',   ['as' => 'controle-registro.editar',  'uses' => 'ControleRegistroController@edit']);
            Route::post('alterar',      ['as' => 'controle-registro.alterar', 'uses' => 'ControleRegistroController@update']);
            Route::post('deletar',      ['as' => 'controle-registro.deletar', 'uses' => 'ControleRegistroController@destroy']);
        });

        Route::group(['prefix' => 'opcao-controle', 'as' => 'docs.'], function() {
            Route::get('',              ['as' => 'opcao-controle',         'uses' => 'OpcaoControleRegistroController@index']);
            Route::get('novo',          ['as' => 'opcao-controle.novo',    'uses' => 'OpcaoControleRegistroController@create']);
            Route::post('salvar',       ['as' => 'opcao-controle.salvar',  'uses' => 'OpcaoControleRegistroController@store']);
            Route::get('editar/{id}',   ['as' => 'opcao-controle.editar',  'uses' => 'OpcaoControleRegistroController@edit']);
            Route::post('alterar',      ['as' => 'opcao-controle.alterar', 'uses' => 'OpcaoControleRegistroController@update']);
            Route::post('deletar',      ['as' => 'opcao-controle.deletar', 'uses' => 'OpcaoControleRegistroController@destroy']);
        });
        

        Route::group(['prefix' => 'bpmn', 'as' => 'docs.'], function() {
            Route::get('',              ['as' => 'bpmn',         'uses' => 'BpmnController@index']);
            Route::get('novo',          ['as' => 'bpmn.novo',    'uses' => 'BpmnController@create']);
            Route::post('salvar',       ['as' => 'bpmn.salvar',  'uses' => 'BpmnController@store']);
            Route::get('editar/{id}',   ['as' => 'bpmn.editar',  'uses' => 'BpmnController@edit']);
            Route::post('alterar',      ['as' => 'bpmn.alterar', 'uses' => 'BpmnController@update']);
            Route::post('deletar',      ['as' => 'bpmn.deletar', 'uses' => 'BpmnController@destroy']);
        });


        Route::group(['prefix' => 'copia-controlada', 'as' => 'docs.'], function() {
            Route::get('',              ['as' => 'copia-controlada',         'uses' => 'CopiaControladaController@index']);
            Route::get('novo',          ['as' => 'copia-controlada.novo',    'uses' => 'CopiaControladaController@create']);
            Route::post('salvar',       ['as' => 'copia-controlada.salvar',  'uses' => 'CopiaControladaController@store']);
            Route::get('editar/{id}',   ['as' => 'copia-controlada.editar',  'uses' => 'CopiaControladaController@edit']);
            Route::post('alterar',      ['as' => 'copia-controlada.alterar', 'uses' => 'CopiaControladaController@update']);
            Route::post('deletar',      ['as' => 'copia-controlada.deletar', 'uses' => 'CopiaControladaController@destroy']);
            Route::post('buscar',       ['as' => 'copia-controlada.buscar',  'uses' => 'CopiaControladaController@getCopiaControlada']);
        });


    });
});
