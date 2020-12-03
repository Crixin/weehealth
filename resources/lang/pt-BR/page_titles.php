<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de Linguagem - Título das páginas
    |--------------------------------------------------------------------------
    |
    | Este arquivo foi criado com o objetivo de armazenar todas os títulos
    | das páginas que serão criadas, sejam títulos principais, subtítulos
    | ou mesmo o que exibir nas tabs do navegador.
    |
    */

    'general'       => [
        'home'      => 'Página Inicial'
    ],

    'core'          => [
        'user'              => [
            'index'         => 'Usuários',
            'create'        => 'Novo Usuário',
            'update'        => 'Alterando Usuário ',
            'person_info'   => 'Informações pessoais',
            'password'      => 'Senha'
        ],
        'enterprise'                => [
            'index'                 => 'Empresas',
            'create'                => 'Nova Empresa',
            'update'                => 'Alterando Empresa '
        ],
        /** CONFIGURAÇÕES */
        'configs'               => [
            'index_parameters'  => 'Parâmetros',
            'index_setup'       => 'Setup',
            'index_ftp'         => 'FTP',
            'index_ftp_desc'    => 'Configurações do FTP',
        ],

        // OUTROS
        'notifications'     =>  [
            'index'         => 'Notificações'
        ],
        //PERFIL
        'perfil'        => [
            'index'           => 'Perfis',
            'create'          => 'Novo Perfil',
            'update'          => 'Alterando Perfil',
        ],
        'group'                 => [
            'index'             => 'Grupos',
            'create'            => 'Novo Grupo',
            'update'            => 'Alterando Grupo ',
            'linked_users'      => 'Usuários Vinculados',
            'linked_users_to'   => 'Usuários Vinculados ao Grupo: '
        ],
    ],

    'portal'        => [
        /** CADASTROS */
        'enterprise'                => [
            'index'                 => 'Empresas',
            'linked_users'          => 'Usuários Vinculados',
            'linked_users_to'       => 'Usuários Vinculados à Empresa: ',
            'users_available'       => 'Usuários Disponíveis',
            'linked_groups'         => 'Grupos Vinculados',
            'linked_groups_to'      => 'Grupos Vinculados à Empresa: ',
            'groups_available'      => 'Grupos Disponíveis',
            'linked_processes'      => 'Processos Vinculados',
            'linked_processes_to'   => 'Processos Vinculados à Empresa: ',
            'processes_available'   => 'Processos Disponíveis'
        ],

        'process'     => [
            'index'              => 'Processos',
            'create'             => 'Novo Processo',
            'update'             => 'Alterando Processo ',
            'search'             => 'Pesquisa Documentos',
            'list_registers'     => 'Registros Encontrados',
            'register_documents' => 'Documentos do Registro',
            'document'           => 'Documento',
            'upload'             => 'Upload de Documentos Diversos'
        ],

        /** DOWNLOADS */
        'downloads'               => [
            'index'  => 'Downloads',
        ],
        
        /** UPLOAD */
        'upload' => [
            'index'  => 'Upload',
        ],

        'ged' => [
            'index'  => 'GED',
            'search' => 'Pesquisar',
        ],

        /** DOSSIÊ DE DOCUMENTOS */
        'dossieDocumentos' => [
            'index'  => 'Dossiê De Documentos',
        ],

        /** LOGS */
        'logs'              => [
            'index'         => 'Logs',
            'list'          => 'Atividades Encontradas',
            'instruction'   => 'Informe o período desejado para realizar a busca',
            'warning'       => 'Esteja ciente que buscas com período maior que 15 dias serão mais lentas e que o período máximo é 3 meses!',
            'empty'         => 'Não foram encontrados registros com os valores pesquisados!',
        ],

        /** RELATÓRIO */
        'report'               => [
            'index'  => 'Relatório',
            'result' => 'Resultado da Pesquisa - Documentos Faltantes',
        ],

        'report-docs'               => [
            'index'  => 'Relatório de Documentos',
        ],

        
        //DASHBOARD
        'dashboard'        => [
            'index'           => 'Dashboards',
            'create'          => 'Novo Dashboard',
            'remove'          => 'Arraste aqui para remove!',
            'add'             => 'Clique para adicionar',
            'config'          => 'Clique para configurar',
            'update'          => 'Alterando Dashboard',
            'load'            => 'Dashboard',
            'linked_users'    => 'Usuários Vinculados',
            'linked_users_to' => 'Usuários Vinculados ao Dashboard: ',
            'users_available' => 'Usuários Disponíveis',
        ],

        //CONFIGURACAO TAREFA
        'configuracaoTarefa'        => [
            'index'           => 'Configurações',
            'create'          => 'Nova Configuração',
            'update'          => 'Alterando Configuração',
        ],

        //TAREFA
        'tarefa'        => [
            'index'           => 'Tarefas',
            'create'          => 'Nova Tarefa',
            'update'          => 'Alterando Tarefa',
        ],

        'empresa-processo-grupo' => [
            'create' => 'Vínculo de grupos com empresa-processo',
            'available-groups' => "Grupos Disponíveis",
            'linked-groups' => "Grupos Vinculados",
        ],
        'editDocuments'   => [
            'index'        => 'Lista de documentos em edição',
        ],

        'modalDashboard'   => [
            'index'        => 'Configure os parametros',
        ],

        'modalViewDashboard'   => [
            'index'        => 'Visualização do dashboard',
        ],

        'warnings'          =>  [
            'report-docs'   => 'O relatório só funcionará para as áreas em que os registros estejam vinculados a um CPF'
        ],
    ],

    'docs'          => [

        'plano'        => [
            'index'           => 'Planos',
            'create'          => 'Nova Plano',
            'update'          => 'Alterando Plano',
        ],

        'fluxo'        => [
            'index'           => 'Fluxos',
            'create'          => 'Nova Fluxo',
            'update'          => 'Alterando Fluxo',
        ],

        'etapa-fluxo'        => [
            'index'           => 'Etapas Fluxo',
            'create'          => 'Nova Etapa do Fluxo',
            'update'          => 'Alterando Etapa do Fluxo',
        ],

        'tipo-documento'        => [
            'index'           => 'Tipos de Documento',
            'create'          => 'Novo Tipo de Documento',
            'update'          => 'Alterando Tipo de Documento',
        ],

    ]
];
