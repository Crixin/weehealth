<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de Linguagem - MESSAGES
    |--------------------------------------------------------------------------
    |
    */

    'administratorPermission'   => 'Se essa permissão estiver ativa, o usuário poderá controlar o cadastro de perfis.',
    'userPermission'            => 'Se essa permissão estiver ativa, os vínculos à nível de usuário serão considerados e qualquer relação com grupos será ignorada.',
    'contateSuporteTecnico'     => 'Contate o suporte técnico!',

    'grupoUser' => [
        'storeSuccess' => "Usuários vinculados ao grupo foram atualizados com sucesso!",
        'storeFail' => "Falha ao vincular usuários ao grupo, verifique se você está vinculando pelo menos um usuário com o perfil de Elaborador.",
        'updateSucess' => "",
        'updateFail' => "",
        'deleteSucess' => "Usuários desvinculados com sucesso!",
        'deleteFail' => "Falha ao desvincular os usuários."
    ],
    'workflow' => [
        'storeSuccess' => "Workflow cadastrado com sucesso!",
        'storeFail' => "Falha ao cadastrar o workflow.",
        'advanceStepSuccess' => "Etapa avançada com sucesso!",
        'advanceStepFail' => "Falha ao avançar a etapa.",
        'retreatStepSuccess' => "Etapa retrocedida com sucesso!",
        'retreatStepFail' => "Falha ao retroceder a etapa.",
        'validationFail' => "Falha ao validar campos do workflow.",
        'startReview' => "<NOME_USUARIO> iniciou a revisão",
        'startValidation' => "<NOME_USUARIO> iniciou a validação",
        'notificationSuccess' => "Notificação enviada com sucesso!",
        'notificationFail' => "Falha ao enviar notificação."
    ],
    'documento' => [
        'startReview' => "Revisão do documento iniciada",
        'startReviewFailed' => "<Falha ao iniciar a revisão do documento"
    ],
    'setor' => [
        'storeSuccess' => "",
        'storeFail' => "",
        'updateSucess' => "",
        'updateFail' => "",
        'deleteSucess' => "Setor excluido com sucesso!",
        'deleteFail' => "Falha ao excluir o setor. Setor possui vínculos."
    ],

    
];
