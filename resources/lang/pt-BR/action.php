<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de Linguagem - Ações
    |--------------------------------------------------------------------------
    |
    | Este arquivo foi criado com o objetivo de armazenar todas as mensagens
    | que precisarão ser exibidas durante a utilização do sistema, tais como
    | labels de botões, tooltips de ações, entre outras.
    |
    */

    'create'    => 'Criar',
    'delete'    => 'Remover',
    'edit'      => 'Alterar',
    'save'      => 'Salvar',
    'cancel'    => 'Cancelar',
    'back'      => 'Voltar',
    'warning'   => 'Alerta',

    'messages' =>  [
        'no_registers'   => 'Não foram encontrados registros com os dados informados!',
        'no_documents'   => 'Não existem documentos inseridos no registro selecionado!',
        'data_send'      => 'Dados enviados na consulta:',
        'not_allowed'    => 'Você não possui a permissão para executar esta ação.',
        'download_delay' => 'Em virtude de percorrer todas áreas buscando os dados que você informou, essa busca pode demorar alguns segundos. Ao finalizar, o sistema salvará o arquivo no FTP da empresa selecionada.',
        'filter_1'       => 'Filtro usado para o processo de folha ponto.',
        'filter_1_desc'  => 'Ao preencher o filtro por matrícula, você está solicitando ao sistema para que ele anexe os documentos de folha ponto. Preencha APENAS o campo matrícula para gerar um arquivo APENAS com os documentos de folha ponto.',
        'filter_2'       => 'Filtro usado para todas as demais áreas.',
        'filter_2_desc'  => 'Preenchendo o filtro por CPF, todas as demais áreas do processo (exceto as áreas de folha ponto) serão indexadas. Preencha APENAS o campo cpf se desejar gerar um arquivo com todos os documentos, exceto as folhas ponto.',
    ]

];
