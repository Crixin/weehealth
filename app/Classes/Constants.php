<?php

namespace App\Classes;

class Constants {

    // NUNCA ALTERE ESSE ARRAY! (ou, se o fizer, revise as regras de negócio do sistema e seus bloqueios -> mas é REVISAR mesmo viu?!)
    public static $ARR_SUPER_ADMINISTRATORS_ID = [
        1
    ];


    public static $INDICES_OCULTOS = [
        'criadorRegistro', 'Data_do_registro'
    ];

    public static $INDICES_OCULTOS_LOGS = [
        'criadorRegistro', 'Data_do_registro', 'ultimaModificacaoRegistro'
    ];


    public static $INDICES_MANTIDOS = [
        'Nome_do_documento', 'Tipo'
    ];

    public static $EXTENSAO_ONLYOFFICE = ["pdf", "djvu", "xps","docx", "xlsx", "csv", "pptx", "txt","docm", "doc", "dotx", "dotm", "dot", "odt", "fodt", "ott", "xlsm", "xls", "xltx", "xltm", "xlt", "ods", "fods", "ots", "pptm", "ppt", "ppsx", "ppsm", "pps", "potx", "potm", "pot", "odp", "fodp", "otp", "rtf", "mht", "html", "htm", "epub"];
    public static $EXTENSAO_IMAGEM = ["png","jpeg","jpg","gif","svg"];
    public static $EXTENSAO_VIDEO = ["mp4","webm","ogg"];

    public static $LOG = "### WEE_LOG ### ";


    // Índice 'Tipo' do documento
    public static $DESCRICAO_TIPO_DOCUMENTO = 'Tipo';
    public static $IDENTIFICADOR_TIPO_DOCUMENTO = 'Tipo';


    // Índice 'Status' do documento
    public static $DESCRICAO_STATUS = 'Status';
    public static $IDENTIFICADOR_STATUS = 'status';
    

    // Índice 'Justificativa da Rejeição' do documento
    public static $DESCRICAO_JUSTIFICATIVA = 'Justificativa da Rejeição';
    public static $IDENTIFICADOR_JUSTIFICATIVA = 'justificativa';

    // Propriedades da tabela de log do GED (ações)
    public static $ACAO_GED_INSERIR = "inserir";
    public static $ACAO_GED_ALTERAR = "alterar";


    public static $IDENTIFICADOR_TAMANHO_DOC = 'Tamanho';


    public static $VALOR_DOCUMENTO_APROVADO = "APROVADO";


    public static $VALOR_DOCUMENTO_REJEITADO = "REJEITADO";


    public static $PROCESSOS = [
        'OUTROS', 'FOLHA_PONTO', 'Documentos Diversos'
    ];


    // Arquivo de configurações (.ini)
    public static $INI_KEY_RELATORIOS = 'relatorio';

    public static $INI_KEY_SUBMENUS = 'submenus';

    public static $INI_KEY_CONFIGURACOES = 'configuracoes';

    public static $INI_KEY_NOME_PROCESSO = 'nome';

    public static $INI_KEY_LISTA_TIPOS_DOCUMENTO = 'lista_tipos_documento';

    public static $INI_KEY_LISTA_IDS_AREA = 'lista_areas';

    public static $INI_KEY_FILTRO = 'filtro';

    public static $INI_KEY_USA_VINCULO = 'usa_vinculo';


    // Array de propriedades utilizadas na busca de registros vinculados por CPF
    public static $FILTROS_VINCULO = [
        'descricao' => 'CPF',
        'idTipoIndice' => 17,
        'identificador' => 'cpf',
    ];




    /**
     *  ============= README =============
     * | Mantenha os dois arrays abaixo |
     * | atualizados, ou seja, com a    |
     * | mesma quantia de elementos,    |
     * | pois esses arrays são comple - |
     * | mentares, sendo um a descrição |
     * | e parte visual e o outro res - |
     * | ponsável pelas interações com  |
     * | o GED.                         |
     * |                                |
     * | Todos os filtros e switch's    |
     * | para identificar qual deve ser |
     * | o valor informado na interação |
     * | com o GED são feitos a partir  |
     * | POSIÇÃO do filtro nos arrays   |
     * | abaixo!                        |
     * ---------------------------------
     */

    public static $FILTER_OPTIONS = [
        'CPF', 'Matrícula ou Período', 'Vínculo - CPF'
    ];

    public static $FILTER_OPTIONS_GED = [
        [
            array(
                'descricao' => 'CPF',
                'idTipoIndice' => 3,
                'identificador' => 'cpf'
            )
        ],
        [
            'matricula' => [
                'descricao' => 'Matrícula',
                'idTipoIndice' => 8,
                'identificador' => 'matricula'
            ], 
            'datas' => [
                [
                    'descricao' => 'DataInicio',
                    'idTipoIndice' => 5,
                    'identificador' => 'datainicio'
                ],
                [
                    'descricao' => 'DataFim',
                    'idTipoIndice' => 5,
                    'identificador' => 'datafim'
                ]
            ]
        ],
        [
            array(
                'descricao' => 'Vínculo - CPF', // Esse valor é independente para as requisições, ou seja, não importa o que se coloque aqui, irá funcionar se os demais valores estiverem corretos
                'idTipoIndice' => 17,
                'identificador' => 'cpf',
                'possui_area_pai' => true,
                'area_pai' => [
                    'id_area' => 'eb73326c-68cf-4849-afdf-342b07934e0a', // Se algum dia o GED for reinstalado, esse id é da área 'PASTA FUNCIONÁRIO' e deve ser atualizado.
                    'descricao' => 'CPF',
                    'idTipoIndice' => 3,
                    'identificador' => 'cpf'
                ],
            )
        ]
    ];

    public static $OPTIONS_TYPE_INDICES_GED = [
        1 => array(
            'text' => "Booleano",
            'htmlType' => 'Select',
            'cssClass' => '',
            'mask' => '',
            'selectOptions' => array(
                'true' => "Sim",
                'false' => "Não"
            )
        ),
        2 => array(
            'text' => "Cnpj",
            'htmlType' => 'text',
            'cssClass' => 'cnpj',
            'mask' => '00.000.000/0000-00',
        ),
        3 => array(
            'text' => "Cpf",
            'htmlType' => 'text',
            'cssClass' => 'cpf',
            'mask' => '000.000.000-00'
        ),
        4 => array(
            'text' => "Cpf Cnpj",
            'htmlType' => 'text',
            'cssClass' => ''
        ),
        5 => array(
            'text' => "Data",
            'htmlType' => 'date',
            'cssClass' => '',
            'mask' => '00/00/0000'
        ),
        6 => array(
            'text' => 'Data e Hora',
            'htmlType' => 'datetime-local',
            'cssClass' => '',
            'mask' => '00/00/0000 00:00:00'
        ),
        7 => array(
            'text' => "Hora",
            'htmlType' => 'time',
            'cssClass' => '',
            'mask' => '00:00:00'
        ),
        8 => array(
            'text' => "Inteiro",
            'htmlType' => 'number',
            'cssClass' => '',
            'mask' => '000000'
        ),
        9 => array(
            'text' => "Inteiro Longo",
            'htmlType' => 'number',
            'cssClass' => '',
            'mask' => '0000000000'
        ),
        10 => array(
            'text' => "Lista De Texto",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        11 => array(
            'text' => "Lista De Texto Longo",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        12 => array(
            'text' => "Multivalorado",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        13 => array(
            'text' => "Referência",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        14 => array(
            'text' => "Referência Múltipla",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        15 => array(
            'text' => "Texto",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        16 => array(
            'text' => "Texto Longo",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        ),
        /*17 => array('text' => "Vínculo", 'type' =>  '' )*/
        18 => array(
            'text' => "Número Formatado",
            'htmlType' => 'text',
            'cssClass' => '',
            'mask' => ''
        )
    ];
}
