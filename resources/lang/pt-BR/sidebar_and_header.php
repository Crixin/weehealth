<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de Linguagem - Menu lateral esquerdo e Cabeçalho
    |--------------------------------------------------------------------------
    |
    | Este arquivo foi criado com o objetivo de armazenar todas as palavras
    | que serão dispostas no menu lateral esquerdo da aplicação, bem como no 
    | cabeçalho, sejam elas tooltips, dropdowns ou simples elementos <p>.
    |
    */

    'li_system'             => 'SISTEMA',
    'tooltip_profile'       => 'Meu Perfil',
    'tooltip_home'          => 'Página Inicial',
    'tooltip_logout'        => 'Sair',

    'notifications'         => 'Notificações',
    'notifications_see_all' => 'Ver todas notificações',

    'btn_view_profile'      => 'Ver Perfil',

    'service_panel'         => [
        'header'            => 'Painel de Serviços',
        'item1'             => 'Com barra lateral clara',
        'item2'             => 'Com barra lateral escura',
    ],
    'core' => [
        'uls_li_system'         => [
            'register'          => [
                'collapse'      => 'CADASTROS'
            ],
            'configs'           => [
                'collapse'      => 'CONFIGURAÇÕES'
            ]
        ]
    ],
    
    'portal' => [
        'uls_li_system'         => [
            'register'          => [
                'collapse'      => 'CADASTROS',
                'item1'         => 'Empresa',
                'item2'         => 'Grupo',
                'item3'         => 'Processo',
                'item4'         => 'Usuário',
                'item5'         => 'Dashboard',
                'item6'         => 'Perfil',
                'item7'         => 'Tarefa',
            ],
            'tarefa'        => [
                'main'          => 'TAREFAS',
                'sub-main'      => 'Listar/Cadastrar',
                'config'        => 'Configurações'
            ],
            'processes'         => [
                'main'          => 'PROCESSOS'
            ],
            'downloads'         => [
                'main'          => 'DOWNLOAD'
            ],
            'ged'         => [
                'main'          => 'GED',
                'upload'        => 'Upload',
                'create'        => 'Criar registro',
                'edit'          => 'Editar registro'
            ],
            'dossie'         => [
                'main'          => 'DOSSIÊ DE DOC.',
                'generate'      => 'Gerar',
                'sended'        => 'Enviados'
            ],
            'logs'              => [
                'main'          => 'LOGS'
            ],
            'conference'        => [
                'collapse'      => 'CONFERÊNCIA',
            ],
            'reports'        => [
                'collapse'      => 'RELATÓRIOS',
                'documents'     => 'Documentos',
            ],
            'configs'           => [
                'collapse'      => 'CONFIGURAÇÕES',
                'item1'         => 'Administradores',
                'item2'         => 'Parâmetros',
                'item3'         => 'Setup',
            ],
            'dashboards'            => [
                'collapse'      => 'DASHBOARD',
                'view'          => 'Visualizar',
                'list'          => 'Listar/Cadastrar',
            ],
      
            'edicaoDocumento' => [
                'main' => 'DOCS EM EDIT.',
            ],
        ],
    
        
    ],
    'docs'                 => [
        "uls_li_system"    => [
            'register'          => [
                'collapse'         => 'CADASTROS',
                'setor'            => 'Setor',
                'configuracao'     => 'Configuração',
                'plano'            => 'Planos',
                'tipo-documento'   => 'Tipos de Documentos',
                'fluxo'            => 'Fluxos',
                'norma'            => 'Normas'
            ],
            'documento'        => 'DOCUMENTOS',
            'documento_externo' => 'DOC. EXTERNOS',
            'workflow'         => 'BPMN 2.0',
            
            'controle_registro' => [
                'collapse' => 'Contr. de Registros',
                'opcao'    => 'Opções',
                'controle' => 'Controles'
            ],
            'controle_registros' => 'CONTR. REGISTROS',
            
        ]
    ]

];
