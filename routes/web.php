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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('download/dossie/{token}', ['as' => 'download.dossie.verify', 'uses' => 'DossieDocumentosController@verifyLink']);
Route::post('download/dossie/download', ['as' => 'download.dossie.download', 'uses' => 'DossieDocumentosController@downloadByLink']);

Route::group(['middleware' => ['auth']], function () {

    /*
    * EMPRESAS
    */
    Route::group(['prefix' => 'empresa', 'middleware' => 'permissionamento:mod_base'], function () {
        Route::get('',                          ['as' => 'empresa',                     'uses' => 'EmpresaController@index']);
        Route::get('nova',                      ['as' => 'empresa.nova',                'uses' => 'EmpresaController@newEnterprise']);
        Route::post('salvar',                   ['as' => 'empresa.salvar',              'uses' => 'EmpresaController@saveEnterprise']);
        Route::get('editar/{id}',               ['as' => 'empresa.editar',              'uses' => 'EmpresaController@editEnterprise']);
        Route::post('alterar',                  ['as' => 'empresa.alterar',             'uses' => 'EmpresaController@updateEnterprise']);
        Route::get('usuarios-vinculados/{id}',  ['as' => 'empresa.usuariosVinculados',  'uses' => 'EmpresaController@linkedUsers']);
        Route::post('vincular-usuarios',        ['as' => 'empresa.vincularUsuarios',    'uses' => 'EmpresaController@updateLinkedUsers']);
        Route::get('grupos-vinculados/{id}',    ['as' => 'empresa.gruposVinculados',    'uses' => 'EmpresaController@linkedGroups']);
        Route::post('vincular-grupos',          ['as' => 'empresa.vincularGrupos',      'uses' => 'EmpresaController@updateLinkedGroups']);
        Route::get('processos-vinculados/{id}', ['as' => 'empresa.processosVinculados', 'uses' => 'EmpresaController@linkedProcesses']);
        Route::post('vincular-processos',       ['as' => 'empresa.vincularProcessos',   'uses' => 'EmpresaController@updateLinkedProcesses']);
        Route::post('deletar',                  ['as' => 'empresa.deletar',             'uses' => 'AjaxController@deleteEnterprise']);
        Route::post('empresa/grupo',            ['as' => 'relacao.empresaGrupo.deletar',   'uses' => 'AjaxController@deleteLinkEnterpriseGroup']);
        Route::post('empresa/processo',         ['as' => 'relacao.empresaProcesso.deletar','uses' => 'AjaxController@deleteLinkEnterpriseProcess']);
        Route::post('empresa/usuario',          ['as' => 'relacao.empresaUsuario.deletar', 'uses' => 'AjaxController@deleteLinkEnterpriseUser']);
    });
    
    /*
    * GRUPOS
    */
    Route::group(['prefix' => 'grupo', 'middleware' => 'permissionamento:mod_base'], function () {
        Route::get('',                          ['as' => 'grupo',                       'uses' => 'GrupoController@index']);
        Route::get('novo',                      ['as' => 'grupo.novo',                  'uses' => 'GrupoController@newGroup']);
        Route::post('salvar',                   ['as' => 'grupo.salvar',                'uses' => 'GrupoController@saveGroup']);
        Route::get('editar/{id}',               ['as' => 'grupo.editar',                'uses' => 'GrupoController@editGroup']);
        Route::post('alterar',                  ['as' => 'grupo.alterar',               'uses' => 'GrupoController@updateGroup']);
        Route::get('usuarios-vinculados/{id}',  ['as' => 'grupo.usuariosVinculados',    'uses' => 'GrupoController@linkedUsers']);
        Route::post('vincular-usuarios',        ['as' => 'grupo.vincularUsuarios',      'uses' => 'GrupoController@updateLinkedUsers']);
        Route::post('deletar',	                ['as' => 'grupo.deletar',               'uses' => 'AjaxController@deleteGroup']);
    });

    /*
    * PERFIL
    */
    Route::group(['prefix' => 'perfil', 'middleware' => 'permissionamento:administrador'], function () {
        Route::get('', ['as' => 'perfil', 'uses' => 'PerfilController@index']);
        Route::get('novo', ['as' => 'perfil.novo', 'uses' => 'PerfilController@create']);
        Route::post('salvar', ['as' => 'perfil.salvar', 'uses' => 'PerfilController@store']);
        Route::get('editar/{id}', ['as' => 'perfil.editar', 'uses' => 'PerfilController@edit']);
        Route::post('alterar/{id}', ['as' => 'perfil.alterar', 'uses' => 'PerfilController@update']);
        Route::post('deletar', ['as' => 'perfil.deletar', 'uses' => 'PerfilController@destroy']);
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
    * CONFIGURAÇÃO
    */
    Route::group(['prefix' => 'configuracao', 'as' => 'configuracao.'], function () {
        Route::get('parametros',        ['as' => 'parametros',         'uses' => 'ConfiguracaoController@indexParameters'])->middleware('onlyAllowSuperAdmins');
        Route::get('administradores',   ['as' => 'administradores',    'uses' => 'ConfiguracaoController@indexAdministrators'])->middleware('onlyAllowSuperAdmins');
    
        Route::group(['prefix' => 'setup', 'as' => 'setup.', 'middleware' => 'permissionamento:conf_setup'], function () {
            Route::get('', ['as' => 'index', 'uses' => 'SetupController@index']);
            Route::post('alterar', ['as' => 'alterar', 'uses' => 'SetupController@update']);
        });
    });
    
    Route::group(['prefix' => 'ged', 'as' => 'ged.'], function () {
        Route::get('getRegistro', ['as' => 'getRegistro', 'uses' => 'AjaxController@getRegistro']);
        Route::get('getDocumento', ['as' => 'getDocumento', 'uses' => 'AjaxController@getDocumento']);
        Route::get('buscaInfoArea', ['as' => 'buscaInfoArea', 'uses' => 'AjaxController@buscaInfoArea']);
        Route::post('pesquisaRegistro', ['as' => 'pesquisaRegistro', 'uses' => 'AjaxController@pesquisaRegistro']);
        Route::post('postDocumento', ['as' => 'postDocumento', 'uses' => 'AjaxController@postDocumento']);
        Route::get('getIndicesComumAreas', ['as' => 'getIndicesComumAreas', 'uses' => 'AjaxController@getIndicesComumAreas']);
        Route::get('getPrestadores', ['as' => 'getPrestadores', 'uses' => 'AjaxController@getPrestadores']);

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
    Route::group(['prefix' => 'deletar', 'as' => 'deletar.'], function () {
        Route::post('documento',            ['as' => 'documento',                   'uses' => 'AjaxController@deleteDocument']);
        Route::post('tarefa',               ['as' => 'tarefa',                      'uses' => 'TarefaController@deleteTarefa']);
        Route::post('registro',             ['as' => 'registro',                    'uses' => 'AjaxController@deleteRegister']);
    });
    
    
    Route::group(['prefix' => 'buscar', 'as' => 'buscar.'], function () {
        Route::get('processByEnterpriseAndProcesso', ['as' => 'processByEnterpriseAndProcess', 'uses' => 'AjaxController@getProcessByEnterpriseAndProcesso']);
    });


    Route::group(['prefix' => 'atualizar', 'as' => 'atualizar.'], function () {
        Route::post('notificacao',              ['as' => 'notificacao',                 'uses' => 'AjaxController@markAsReadNotification']);
        Route::post('parametro',                ['as' => 'parametro',                   'uses' => 'AjaxController@updateParamValue']);
        Route::post('parametro-ativo',          ['as' => 'parametroAtivo',              'uses' => 'AjaxController@updateParamActiveValue']);
        Route::post('permissao-usuario',        ['as' => 'permissaoUsuario',            'uses' => 'AjaxController@updateUserPermissions']);
        Route::post('permissao-administrador',  ['as' => 'permissaoAdministrador',      'uses' => 'AjaxController@updateAdministratorPermissions'])->middleware('onlyAllowSuperAdmins');
        Route::post('empresa/grupo',            ['as' => 'relacao.empresaGrupo',        'uses' => 'AjaxController@updateLinkEnterpriseGroup']);
        Route::post('empresa/usuario',          ['as' => 'relacao.empresaUsuario',      'uses' => 'AjaxController@updateLinkEnterpriseUser']);
        Route::post('setup',                    ['as' => 'setup',                       'uses' => 'AjaxController@updateSetup']);
    });
    
    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        Route::post('resendDossie', ['as' => 'resendDossie', 'uses' => 'AjaxController@resendDossie']);
    });

    /*
    * GED
    */
    Route::group(['prefix' => 'ged', 'as' => 'ged.'], function () {
        Route::get('getRegistro', ['as' => 'getRegistro', 'uses' => 'AjaxController@getRegistro']);
        Route::get('getDocumento', ['as' => 'getDocumento', 'uses' => 'AjaxController@getDocumento']);
        Route::get('buscaInfoArea', ['as' => 'buscaInfoArea', 'uses' => 'AjaxController@buscaInfoArea']);
        Route::post('pesquisaRegistro', ['as' => 'pesquisaRegistro', 'uses' => 'AjaxController@pesquisaRegistro']);
        Route::post('postDocumento', ['as' => 'postDocumento', 'uses' => 'AjaxController@postDocumento']);
        Route::get('getIndicesComumAreas', ['as' => 'getIndicesComumAreas', 'uses' => 'AjaxController@getIndicesComumAreas']);
    });

    /*
    * DOSSIÊ DOCUMENTOS
    */
    Route::group(['prefix' => 'dossieDocumentos','as' => 'dossieDocumentos.'], function () {
        Route::get('', ['as' => 'novo', 'uses' => 'DossieDocumentosController@novo']);
        Route::get('list', ['as' => 'list', 'uses' => 'DossieDocumentosController@list']);
        Route::post('download', ['as' => 'download', 'uses' => 'DossieDocumentosController@downloadDossie']);
        Route::post('deletar', ['as' => 'dossie.deletar', 'uses' => 'AjaxController@deleteDossie']);
    });


    /*
    * EDIÇÂO DOCUMENTOS
    */
    Route::group(['prefix' => 'edicaoDocumento', 'as' => 'edicaoDocumento.'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'EdicaoDocumentoController@index']);
        Route::post('deleteRegistroAndDoc', ['as' => 'deleteRegistroAndDoc', 'uses' => 'EdicaoDocumentoController@deleteRegistroAndDoc']);
    });

    /*
    * REGISTRO DE USUÁRIO
    */
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    

    /**
     * PROCESSO - documentos
     */
    Route::group(['prefix' => 'processo', 'as' => 'processo.', 'middleware' => 'userCanByEntreprise'], function () {
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
    * DOWNLOAD
    */
    /* Route::group(['prefix' => 'download'], function () {
        Route::get('',              ['as' => 'download',            'uses' => 'DownloadController@index']);
        Route::post('criar-zip',    ['as' => 'download.criarZip',   'uses' => 'DownloadController@makeZIP']);
    }); */


    /*
    * LOGS
    */
    /*Route::group(['prefix' => 'logs'], function () {
        Route::get('',              ['as' => 'logs',        'uses' => 'LogsController@index']);
        Route::post('atividades',   ['as' => 'logs.search', 'uses' => 'LogsController@search']);
    }); */


    /*
    * RELATÓRIOS
    */
    Route::group(['prefix' => 'relatorio', 'as' => 'relatorio.', 'middleware' => 'permissionamento:view_dashboard'], function () {
        //Route::get('conferencia/{idEmpresa}/{idProcesso}',   ['as' => 'conferencia',  'uses' => 'RelatorioController@index']);
        //Route::post('conferencia/busca',                     ['as' => 'buscar',       'uses' => 'RelatorioController@search']);
        Route::get('documentos/',                  ['as' => 'documentos',  'uses' => 'RelatorioDocumentosController@index']);
        Route::post('documentos/gerar',            ['as' => 'documentos.gerar',  'uses' => 'RelatorioDocumentosController@gerar']);
    });


    /*
	* NOTIFICAÇãO
	*/
	Route::group(['prefix' => 'notificacao'], function () {
        Route::get('',                          ['as' => 'notificacao',                           'uses' => 'NotificacaoController@index']);
        Route::get('marcar-todas-como-lidas',   ['as' => 'notificacao.marcar-todas-como-lidas',   'uses' => 'NotificacaoController@markAllAsRead']);
    });

    /*
    * DASHBOARDS
    */
    Route::group(['prefix' => 'dashboards', 'middleware' => 'permissionamento:mod_dashboard'], function () {
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
    Route::group(['prefix' => 'dashboard', 'middleware' => 'permissionamento:view_dashboard'], function () {
        Route::get('view/{id}', ['as' => 'dashboard.view', 'uses' => 'DashboardController@view']);
    });


    /*
    * CONFIG. TAREFA
    */
    Route::group(['prefix' => 'config-tarefa', 'middleware' => 'permissionamento:mod_tarefas'], function () {
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
    Route::group(['prefix' => 'tarefa', 'middleware' => 'permissionamento:mod_tarefas'], function () {
        Route::get('',                          ['as' => 'tarefa',                    'uses' => 'TarefaController@index']);
        Route::get('nova',                      ['as' => 'tarefa.criar',              'uses' => 'TarefaController@newTarefa']);
        Route::post('salvar',                   ['as' => 'tarefa.salvar',             'uses' => 'TarefaController@saveTarefa']);
        Route::get('editar/{id}',               ['as' => 'tarefa.editar',             'uses' => 'TarefaController@editTarefa']);
        Route::post('alterar',                  ['as' => 'tarefa.alterar',            'uses' => 'TarefaController@updateTarefa']);
    });


    /*
    * EMPRESA PROCESSO GRUPOS
    */
    Route::group(['prefix' => 'empresa-processo-grupo', 'middleware' => 'permissionamento:mod_base'], function () {
        Route::get('novo/{empresaProcesso}', ['as' => 'empresa-processo-grupo.criar', 'uses' => 'EmpresaProcessoGrupoController@create']);
        Route::post('salvar', ['as' => 'empresa-processo-grupo.salvar', 'uses' => 'EmpresaProcessoGrupoController@store']);
        Route::post('alterar', ['as' => 'empresa-processo-grupo.alterar', 'uses' => 'EmpresaProcessoGrupoController@update']);
        Route::get('get/pre-filtro-processo', ['as' => 'empresa-processo-grupo.get.pre-filtro-processo', 'uses' => 'AjaxController@getPreFiltroProcessos']);
        Route::post('deletar', ['as' => 'empresa-processo-grupo.deletar', 'uses' => 'AjaxController@deleteLinkEmpresaProcessoGrupo']);
    });
});


// AUTENTICAÇÃO
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


// LOG-OUT
Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
    return Redirect::to('/login');
});
