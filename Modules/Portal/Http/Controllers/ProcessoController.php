<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use App\Mail\RejectedDocument;
use App\Classes\{Constants, RESTServices};
use Modules\Core\Repositories\{GrupoUserRepository, UserRepository};
use Modules\Portal\Repositories\{
    EmpresaGrupoRepository,
    EdicaoDocumentoRepository,
    EmpresaProcessoRepository,
    ProcessoRepository
};
use Illuminate\Support\Facades\{Auth, Mail, Log, Validator};

class ProcessoController extends Controller
{
    protected $edicaoDocumentoRepository;
    protected $empresaGrupoRepository;
    protected $grupoUserRepository;
    protected $userRepository;
    protected $processoRepository;
    protected $empresaProcessoRepository;
    private $ged;


    /*
    * Construtor
    */
    public function __construct()
    {
        $this->ged = new RESTServices();
        $this->empresaGrupoRepository = new EmpresaGrupoRepository();
        $this->grupoUserRepository = new GrupoUserRepository();
        $this->userRepository = new UserRepository();
        $this->processoRepository = new ProcessoRepository();
        $this->empresaProcessoRepository = new EmpresaProcessoRepository();
    }


    public function index()
    {
        $processos = $this->processoRepository->findBy(
            [],
            [],
            [
                ['nome','ASC']
            ]
        );
        return view('portal::processo.index', compact('processos'));
    }


    public function newProcess()
    {
        return view('portal::processo.create');
    }

    public function saveProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'      => 'required|string|max:100|unique:portal_processo',
            'descricao' => 'required|string|max:300'
        ]);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }


        $this->processoRepository->create(
            [
                'nome' => $request->get('nome'),
                'descricao' => $request->get('descricao')
            ]
        );

        Helper::setNotify('Processo criado com sucesso!', 'success|check-circle');
        return redirect()->route('portal.processo');
    }


    public function editProcess($_id)
    {
        $processo = $this->processoRepository->find($_id);
        return view('portal::processo.update', compact('processo'));
    }


    public function updateProcess(Request $request)
    {
        $arrRegras = array('descricao' => 'required|string|max:300');
        $processo = $this->processoRepository->find($request->get('idProcesso'));

        if ($request->get('nome') != $processo->nome) {
            $arrRegras['nome'] = 'required|string|max:100|unique:portal_processo';
        }
        $validator = Validator::make($request->all(), $arrRegras);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        $processo->nome = $request->get('nome');
        $processo->descricao = $request->get('descricao');
        $processo->save();

        Helper::setNotify('Informações do processo atualizadas com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }


    public function search($_idEmpresa, $_idProcesso)
    {
        $empresaProcesso = $this->empresaProcessoRepository->findOneBy(
            [
                ['empresa_id','=', $_idEmpresa],
                ['processo_id','=', $_idProcesso]
            ]
        );
        $indices = json_decode($empresaProcesso->indice_filtro_utilizado, true);

        foreach ($indices as $key => $indice) {
            $indices[$key] = json_decode($indice);
        }

        $gruposFiltros = array_column($empresaProcesso->portalEmpresaProcessoGrupo->toArray(), 'grupo_id');

        $grupoUser = $this->grupoUserRepository->findOneBy([
            ['grupo_id', "", $gruposFiltros, "IN"],
            ['user_id', "=", Auth::id()]
        ])->grupo_id ?? "";

        $posicao = array_search($grupoUser, $gruposFiltros);

        $filtros = $posicao > -1 ? $empresaProcesso->empresaProcessoGrupo[$posicao]->filtros : "[]";
        // Limpa a sessão e coloca os valores base para as páginas que serão exibidas a partir daqui na sessão para que seja mais fácil manipular e persistir estes valores
        session()->forget('identificadores');
        session(['identificadores' => array(
            'idAreaGED'       => $empresaProcesso->id_area_ged,
            'idxFiltro'       => $empresaProcesso->indice_filtro_utilizado,
            'valorPesquisado' => '-',
            '_idEmpresa'      => $_idEmpresa,
            '_idProcesso'     => $_idProcesso,
            'todosFiltrosPesquisaveis' => $empresaProcesso->todos_filtros_pesquisaveis
        )]);

        $tipoIndicesGED = Constants::$OPTIONS_TYPE_INDICES_GED;

        return view('portal::processo.search-documents', compact("indices", "tipoIndicesGED", "filtros"));
    }


    public function listRegisters(Request $_request)
    {
        $cabecalho = [];
        $listaIndices = [];

        $indices = json_decode($_request->componentsForSubmit);

        if (!$indices) {
            Helper::setNotify(
                'Vish, você esqueceu de fazer um filtro.',
                'warning|close-circle'
            );
            return back();
        }


        $identificadores = session('identificadores');

        foreach ($indices as $indice) {
            //VALIDAÇÃO TIPO BOOLEAN
            if ($indice->idTipoIndice == 1) {
                $indice->valor = boolval($indice->valor);
            }

            if ($indice->idTipoIndice == 17) {
                $areaReferenciada = $this->ged->buscaInfoArea($indice->idAreaReferenciada);

                if ($areaReferenciada['error']) {
                    Helper::setNotify(
                        'Ops, tivemos um problema ao pesquisar a área vínculo no GED. Por favor, contate o suporte técnico!',
                        'danger|close-circle'
                    );
                    return back();
                }

                $areaReferenciada = $areaReferenciada['response'][0];

                foreach ($areaReferenciada->listaIndicesRegistro as $key => $refIndice) {
                    if ($indice->identificador == $refIndice->identificador) {
                        $infoIndiAreaRef = $refIndice;
                    }
                }

                $params = [
                    'listaIdArea' => [
                        $indice->idAreaReferenciada
                    ],
                    'listaIndice' => [
                        (object) [
                            'idTipoIndice' => $infoIndiAreaRef->idTipoIndice,
                            'identificador' => $infoIndiAreaRef->identificador,
                            'valor' => $indice->valor
                        ]
                    ]
                ];

                $buscaIdReg = $this->ged->pesquisaRegistro($params);

                if ($buscaIdReg['error']) {
                    Helper::setNotify(
                        'Ops, tivemos um problema ao pesquisar um registro no GED. Por favor, contate o suporte técnico!',
                        'danger|close-circle'
                    );
                    return back();
                }
                //Seta o id do registro para pesquisar na área de vínculo
                $indice->valor = $buscaIdReg['response']->listaRegistro[0]->id;
            }

            $listaIndices[] = (object) [
                'idTipoIndice' => $indice->idTipoIndice,
                'identificador' => $indice->identificador,
                'valor' => $indice->valor
            ];
        }

        $params = [
            'listaIdArea' => json_decode($identificadores['idAreaGED']),
            'listaIndice' => $listaIndices,
            'inicio' => 0,
            'fim' => 3000
        ];

        $registros = $this->ged->pesquisaRegistro($params);

        if ($registros['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema ao pesquisar os registros no GED. Por favor, contate o suporte técnico!',
                'danger|close-circle'
            );
            return back();
        }

        $registros = $registros['response']->listaRegistro;

        $cabecalho = json_decode($identificadores['todosFiltrosPesquisaveis'], true);

        $cabecalho = array_filter($cabecalho, function ($arr) {
            return $arr['exibidoNaPesquisa'] ?? false;
        });

        $cabecalho = array_column($cabecalho, "descricao");
        foreach ($registros as $key => $registro) {
            $registro->listaIndice = array_filter($registro->listaIndice, function ($indice) use ($cabecalho) {
                return in_array($indice->descricao, $cabecalho);
            });
        }

        $detalhado = $_request->tipoPesquisa == "detalhada" ?: false;

        return view('portal::processo.list-registers', compact('registros', 'cabecalho', 'detalhado'));
    }


    public function listDocuments($idRegistro)
    {
        $cabecalho = [];
        $documentos = [];
        $identificadores = session('identificadores');
        $possuiDocumento = false;

        $params = [
            'docs' => "true"
        ];

        $registro = $this->ged->get(
            env('GED_URL') . "/registro/pesquisa/" . $identificadores['idAreaGED'] . '/' . $idRegistro,
            $params
        )['response'];

        if (!empty($registro->listaDocumento)) {
            $possuiDocumento = true;
            $documentos = (!is_array($registro->listaDocumento)) ? array($registro->listaDocumento) : $registro->listaDocumento;

            // Mantém apenas os índices que devem ser visualizados na propriedade 'listaIndice' do documento
            foreach ($documentos as $key => $value) {
                $value->listaIndice = array_filter($value->listaIndice, function ($v, $k) {
                    return !in_array($v->identificador, Constants::$INDICES_OCULTOS);
                }, ARRAY_FILTER_USE_BOTH);
            }

            // Cabeçalho da tabela de resultados

            $aux = $documentos;
            $cabecalho = array_shift($aux)->listaIndice;
        }

        // Pode Excluir?
        $podeExcluir = Helper::isEnabled('PERMITIR_EXCLUIR', 'permissao_excluir_doc', $identificadores['_idEmpresa']);

        return view('portal::processo.list-documents', compact('possuiDocumento', 'cabecalho', 'documentos', 'podeExcluir'));
    }


    public function accessDocument($idDocumento)
    {
        $identificadores = session('identificadores');

        // Mantém apenas os índices que devem ser visualizados na propriedade 'listaIndice' do documento
        //$documento = $this->ged->pesquisaDocumento($idDocumento)->return;

        $documento = $this->ged->get(env('GED_URL') . "/documento/" . $idDocumento)['response'];

        $documento->listaIndice = array_filter($documento->listaIndice, function ($v, $k) use ($documento) {
            if ($v->identificador == 'status') {
                if (!empty($v->valor)) {
                    $documento->status = $v->valor;
                }
            }
            if ($v->identificador == 'Nome_do_documento') {
                if (!empty($v->valor)) {
                    $documento->tipo = explode(".", $v->valor)[1];
                    $documento->endereco = $v->valor;
                }
            }
            return !in_array($v->identificador, Constants::$INDICES_OCULTOS);
        }, ARRAY_FILTER_USE_BOTH);

        // Pode fazer Download? Excluir? Aprovar? Imprimir? (respectivamente)
        $permissoes['usa_download'] = Helper::isEnabled('PERMITIR_DOWNLOAD', 'permissao_download', $identificadores['_idEmpresa']);
        $permissoes['usa_excluir'] = Helper::isEnabled('PERMITIR_EXCLUIR', 'permissao_excluir_doc', $identificadores['_idEmpresa']);
        $permissoes['usa_aprovar'] = Helper::isEnabled('PERMITIR_APROVAR', 'permissao_aprovar_doc', $identificadores['_idEmpresa']);
        $permissoes['usa_imprimir'] = Helper::isEnabled('PERMITIR_IMPRIMIR', 'permissao_impressao', $identificadores['_idEmpresa']);
        $permissoes['usa_editar'] = Helper::isEnabled('PERMITIR_EDITAR', 'permissao_editar', $identificadores['_idEmpresa']);

        $extensao_onlyoffice = Constants::$EXTENSAO_ONLYOFFICE;
        $extensao_imagem     = Constants::$EXTENSAO_IMAGEM;
        $extensao_video      = Constants::$EXTENSAO_VIDEO;
        $valor_doc_aprovado  = Constants::$VALOR_DOCUMENTO_APROVADO;

        if (in_array($documento->tipo, $extensao_onlyoffice)) {
            $pathFiles = public_path('plugins/onlyoffice-php/Storage');
            $nameFile = $documento->id . "_" . $documento->endereco;
            $documento->endereco = $nameFile;
            if (!file_exists($pathFiles . "/" . $nameFile)) {
                $file = base64_decode($documento->bytes);
                $path = $pathFiles . "/" . $nameFile;
                file_put_contents($path, $file);
            }

            if ($permissoes['usa_editar'] && file_exists($pathFiles . "/" . $nameFile)) {
                //inserir tabela edicao de documentos
                $this->edicaoDocumentoRepository = new EdicaoDocumentoRepository();
                $registrosEdicaoDoc = $this->edicaoDocumentoRepository->findBy(
                    [
                        ['user_id','=',Auth::user()->id,'and'],
                        ['documento_id','=',$documento->id]
                    ]
                );
                if (count($registrosEdicaoDoc) == 0) {
                    $montaRequest = [
                        'user_id' => Auth::user()->id,
                        'documento_id' => $documento->id,
                        'documento_nome' => $nameFile
                    ];
                    $this->edicaoDocumentoRepository->create($montaRequest);
                }
            }
        }
        return view(
            'portal::processo.access-document',
            compact(
                'documento',
                'permissoes',
                'extensao_onlyoffice',
                'extensao_imagem',
                'extensao_video',
                'valor_doc_aprovado'
            )
        );
    }

    public function approveDocument(Request $request)
    {
        $idDocumento = $request->get('idDocumento');

        $documentoCompleto = $this->ged->get(env('GED_URL') . "/documento/" . $idDocumento)['response'];

        $arrIndicesDoc = $this->createIndexArray($documentoCompleto->listaIndice, Constants::$VALOR_DOCUMENTO_APROVADO);

        $nome_status = '';
        foreach ($documentoCompleto->listaIndice as $key => $value) {
            if ($value->identificador == Constants::$INDICES_MANTIDOS[0]) {
                $documentoCompleto->nome = $value->valor;
            }

            if ($value->identificador == Constants::$IDENTIFICADOR_STATUS && !empty($value->valor)) {
                $nome_status = $value->valor;
            }
        }

        if (!empty($nome_status)) {
            Helper::setNotify('Documento já possui status ' . $nome_status . ', verifique !', 'danger|close-circle');
        } else {
            // Realiza a requisição que vai atualizar os índices do documento (ps.: todos esses parâmetros são enviados porque a dlç do GED iria excluir todos os índices casos eles não fossem enviados, mesmo não precisando mudar seu valor)
            $update = [
                'id' => $documentoCompleto->id,
                'endereco' => $documentoCompleto->nome,
                'bytes' => $documentoCompleto->bytes,
                'idArea' => $documentoCompleto->idArea,
                'idRegistro' => $documentoCompleto->idRegistro,
                'idUsuario' => env('ID_GED_USER'),
                'listaIndice' => $arrIndicesDoc,
                'removido' => false
            ];
            $request = $this->ged->put(env('GED_URL') . "/documento/", $update);

            if (!$request['error']) {
                Helper::setNotify('Documento aprovado com sucesso!', 'success|check-circle');
            } else {
                Helper::setNotify('Erro ao aprovar o documento, contate o suporte técnico!', 'danger|close-circle');
            }
        }
        return back()->withInput();
    }

    public function rejectDocument(Request $request)
    {
        $idDocumento   = $request->get('idDocumento');
        $_justificativa = $request->get('justificativa-rejeicao');

        $documentoCompleto = $this->ged->get(env('GED_URL') . "/documento/" . $idDocumento)['response'];

        $arrIndicesDoc     = $this->createIndexArray(
            $documentoCompleto->listaIndice,
            Constants::$VALOR_DOCUMENTO_REJEITADO,
            $_justificativa
        );

        // [README] Essa validação é feita por questões de segurança. Em resumo, valida se o valor presente na sessão (id da empresa) é o mesmo que o valor encontrado no banco de outra forma (através do id de área vinculado à empresa, que é único). Se os dois valores são iguais, segue o processamento
        $empresaProcesso = $this->empresaProcessoRepository->findOneBy(
            [
                ['id_area_ged', 'like', '%' . $documentoCompleto->idArea . '%' ]
            ]
        );
        $identificadores = session('identificadores');

        if ($empresaProcesso->empresa_id != $identificadores['_idEmpresa']) {
            Helper::setNotify('Por questões de segurança, sua requisição foi bloqueada. No entanto, isso pode ser apenas uma medida preventiva. Para mais informações, por favor, contate o suporte!', 'danger|close-circle');
            return back()->withInput();
        }

        $nome_status = '';
        foreach ($documentoCompleto->listaIndice as $key => $value) {
            if ($value->identificador == Constants::$INDICES_MANTIDOS[0]) {
                $documentoCompleto->nome = $value->valor;
            }
            if ($value->identificador == Constants::$INDICES_MANTIDOS[1]) {
                $documentoCompleto->tipo = $value->valor;
            }
            if ($value->identificador == Constants::$IDENTIFICADOR_STATUS && !empty($value->valor)) {
                $nome_status = $value->valor;
            }
        }

        if (!empty($nome_status)) {
            Helper::setNotify('Documento já possui status ' . $nome_status . ', verifique !', 'danger|close-circle');
        } else {
            // Realiza a requisição que vai atualizar os índices do documento (ps.: todos esses parâmetros são enviados porque os piça mole que fizeram o GED excluem todos os índices casos eles não fossem enviados, mesmo não precisando mudar seu valor)
            $update = [
                'id' => $documentoCompleto->id,
                'endereco' => $documentoCompleto->nome,
                'bytes' => $documentoCompleto->bytes,
                'idArea' => $documentoCompleto->idArea,
                'idRegistro' => $documentoCompleto->idRegistro,
                'idUsuario' => env('ID_GED_USER'),
                'listaIndice' => $arrIndicesDoc,
                'removido' => false
            ];

            $documentoAtualizado = $this->ged->put(env('GED_URL') . "/documento/", $update);

            if ($documentoAtualizado['error']) {
                Helper::setNotify('O documento não foi rejeitado, contate o suporte técnico!', 'danger|close-circle');
                return back()->withInput();
            }

            Helper::setNotify('Documento marcado como rejeitado e e-mails enviados com sucesso!', 'success|check-circle');
            Log::debug(Constants::$LOG . "Método para rejeitar documento: \n  {nome} $documentoCompleto->nome \n  {id} $documentoCompleto->id \n  {idReg} $documentoCompleto->idRegistro \n  {idArea} $documentoCompleto->idArea");

            // Envia o e-mail sobre a rejeição
            $idUsuariosHabilitados = Helper::getEnterpriseRecipients($empresaProcesso->empresa_id);
            if (is_array($idUsuariosHabilitados)) {
                Log::debug(Constants::$LOG . "Destinatários da empresa [{$empresaProcesso->empresa_id}]: " . implode("; ", $idUsuariosHabilitados));

                $destinatarios = $this->userRepository->findBy(
                    [
                        ['id','',$idUsuariosHabilitados, 'IN']
                    ]
                );
                Mail::to($destinatarios)->send(
                    new RejectedDocument(
                        $empresaProcesso->empresa_id,
                        $empresaProcesso->processo_id,
                        $identificadores['valorPesquisado'],
                        $_justificativa,
                        $documentoCompleto->nome,
                        $documentoCompleto->tipo,
                        Auth::user()->id
                    )
                );
            } else {
                Log::debug(Constants::$LOG . "Não foi possível obter os destinatários da empresa [{$empresaProcesso->empresa_id}]! \n\n\n");
            }
        }
        return back()->withInput();
    }


    public function upload($_idEmpresa, $_idProcesso)
    {
        $empresaProcesso = $this->empresaProcessoRepository->findOneBy(
            [
                ['empresa_id','=',$_idEmpresa],
                ['processo_id','=', $_idProcesso]
            ]
        );
        $idAreaGED = $empresaProcesso->id_area_ged;

        return view('portal::processo.upload-documents', compact("idAreaGED"));
    }


    public function makeUpload(Request $request)
    {
        if (empty($request->cpf) || empty($request->idAreaGED) || $request->arquivo_upload->getSize() <= 0) {
            Helper::setNotify('Por favor, preencha o CPF e selecione um arquivo!', 'danger|close-circle');
            return back();
        }

        // Primeiramente, preciso pegar o id da área 'pai' da área que foi selecionada como pertencente ao processo 'Documentos Diversos', pois seus registros possuem VÍNCULO com a outra área e nada a mais que eu possa usar para pesquisa
        $areaCompleta = $this->ged->get(env("GED_URL") . "/area/" . $request->idAreaGED);
        if ($areaCompleta['error']) {
            Helper::setNotify(
                'A estrutura de áreas criada não está nos padrões esperados. Por favor, contate o suporte técnico!',
                'danger|close-circle'
            );
            return back();
        }
        $areaCompleta = $areaCompleta['response'][0];

        // Em seguida, pesquiso o CPF informado na área pai, pois é lá que ele 'realmente' está armazenado. O resto são vínculos, apenas.
        foreach (Constants::$FILTER_OPTIONS_GED[0] as $key => $value) {
            $arrIndices = [
                'listaIdArea' => [
                    $areaCompleta->idAreaPai
                ],
                'listaIndice' => [
                    (object) [
                        'descricao' => $value['descricao'],
                        'idTipoIndice' => $value['idTipoIndice'],
                        'identificador' => $value['identificador'],
                        'valor' => $request->cpf
                    ]
                ],
                'inicio' => 0,
                'fim' => 10
            ];
        }

        $resultado = $this->ged->post(env('GED_URL') . "/registro/pesquisa", $arrIndices);

        if (empty($resultado['response']->listaRegistro)) {
            Helper::setNotify(
                'Não encontramos nenhum registro, na área pai, com o CPF informado!',
                'danger|close-circle'
            );
            return back();
        }

        // Agora já temos em mãos o valor do id do registro da área pai e, portanto, podemos fazer a busca na área filha utilizando esse ID (tipo vínculo, 17)

        $registro = $resultado['response']->listaRegistro[0];
        foreach (Constants::$FILTER_OPTIONS_GED[2] as $key => $value) {
            $arrIndicesVinculo = [
                'listaIdArea' => [
                    $areaCompleta->id
                ],
                'listaIndice' => [
                    (object) [
                        'descricao' => $value['descricao'],
                        'idTipoIndice' => $value['idTipoIndice'],
                        'identificador' => $value['identificador'],
                        'valor' => $registro->id
                    ]
                ]
            ];
        }
        $arquivoSelecionado = $request->arquivo_upload;
        $base64file = base64_encode(file_get_contents($arquivoSelecionado));

        $resultadoAreaDocsDiversos = $this->ged->post(env('GED_URL') . "/registro/pesquisa", $arrIndicesVinculo)['response'];
        if (empty($resultadoAreaDocsDiversos->listaRegistro)) {
            // Não existe registro vinculado com o CPF buscado e, portanto, é necessário criar um novo registro

            foreach (Constants::$FILTER_OPTIONS_GED[0] as $key => $value) {
                $insereRegistro = [
                    'idArea' => $areaCompleta->id,
                    'removido' => false,
                    'idUsuario' => env('ID_GED_USER'),
                    'listaIndice' => [
                        (object) [
                            'descricao' => $value['descricao'],
                            'idTipoIndice' => 17,
                            'identificador' => $value['identificador'],
                            'valor' => $registro->id
                        ]
                    ],
                ];
            }

            $registroInserido = $this->ged->post(env('GED_URL') . "/registro", $insereRegistro, true);

            if (!$registroInserido['error']) {
                $registroInserido = $registroInserido['response'];
                $insereDocumento = [
                    'endereco' => $arquivoSelecionado->getClientOriginalName(),
                    'idArea' => $areaCompleta->id,
                    'idRegistro' => $registroInserido,
                    'idUsuario' => env('ID_GED_USER'),
                    'removido' => false,
                    'bytes'    => $base64file
                ];

                $documentoInserido = $this->ged->post(env('GED_URL') . "/documento", $insereDocumento, true);
                if ($documentoInserido['error']) {
                    Helper::setNotify(
                        'Ops, tivemos um problema ao criar o registro e anexar o documento. Por favor, contate o suporte técnico!',
                        'danger|close-circle'
                    );
                } else {
                    Helper::setNotify(
                        "Registro criado e documento " . $arquivoSelecionado->getClientOriginalName() . " anexado com sucesso!",
                        'success|check-circle'
                    );
                }
            } else {
                Helper::setNotify(
                    'Ops, tivemos um problema ao criar o registro. Por favor, contate o suporte técnico!',
                    'danger|close-circle'
                );
            }
        } else {
            // Finalmente, encontramos o registro buscado desde o início e agora necessitamos apenas inserir o documento no mesmo
            $registrosDocsDiversos = $resultadoAreaDocsDiversos;
            $insereDocumento = [
                'idArea' => $areaCompleta->id,
                'idRegistro' => $registrosDocsDiversos->listaRegistro[0]->id,
                'endereco' => $arquivoSelecionado->getClientOriginalName(),
                'idUsuario' => env('ID_GED_USER'),
                'bytes'    => $base64file,
                'removido' => false
            ];

            $documentoInserido = $this->ged->post(env('GED_URL') . "/documento", $insereDocumento);


            if ($documentoInserido['error']) {
                Helper::setNotify(
                    'Ops, tivemos um problema ao anexar o documento. Por favor, contate o suporte técnico!',
                    'danger|close-circle'
                );
            } else {
                Helper::setNotify(
                    "Documento " . $arquivoSelecionado->getClientOriginalName() . " anexado com sucesso!",
                    'success|check-circle'
                );
            }
        }

        return back();
    }


    private function createIndexArray($_listaIndice, $_status, $_justificativa = '')
    {
        $arrIndicesDoc = [
            (object) [
                'descricao' => Constants::$DESCRICAO_STATUS,
                'identificador' => Constants::$IDENTIFICADOR_STATUS,
                'idTipoIndice' => 15,
                'valor' => $_status
            ],
            (object) [
                'descricao' => Constants::$DESCRICAO_JUSTIFICATIVA,
                'identificador' => Constants::$IDENTIFICADOR_JUSTIFICATIVA,
                'idTipoIndice' => 15,
                'valor' => utf8_decode($_justificativa)
            ],
        ];

        foreach ($_listaIndice as $key => $value) {
            if (in_array($value->identificador, Constants::$INDICES_MANTIDOS)) {
                array_push(
                    $arrIndicesDoc,
                    (object) [
                        'descricao' => $value->descricao,
                        'identificador' => $value->identificador,
                        'idTipoIndice' => $value->idTipoIndice,
                        'valor' => $value->valor
                    ]
                );
            }
        }
        return $arrIndicesDoc;
    }

    public function updateDocument(Request $request)
    {
        $documentoCompleto = json_decode($request->documento);
        $endereco = $request->get('endereco');
        $endereco_aux = explode('_', $endereco);

        $path = public_path('plugins/onlyoffice-php/Storage/' . $endereco);
        $base64file = base64_encode(file_get_contents($path));

        $arrIndicesDoc     = $this->createIndexArray($documentoCompleto->listaIndice, '', '');

        $update = [
            'id' => $documentoCompleto->id,
            'endereco' => $endereco_aux[1],
            'bytes' => $base64file,
            'idArea' => $documentoCompleto->idArea,
            'idRegistro' => $documentoCompleto->idRegistro,
            'idUsuario' => env('ID_GED_USER'),
            'listaIndice' =>  $arrIndicesDoc,
            'removido' => false
        ];

        $documentoAtualizado = $this->ged->put(env('GED_URL') . "/documento/", $update);

        if ($documentoAtualizado['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema ao atualizar o documento. Por favor, contate o suporte técnico!',
                'danger|close-circle'
            );
        } else {

            $this->edicaoDocumentoRepository = new EdicaoDocumentoRepository();
            $resultado = $this->edicaoDocumentoRepository->deleteRegAndDocument($endereco, $path);

            if ($resultado) {
                Helper::setNotify("Documento " . $endereco_aux[1] . " atualizado com sucesso!", 'success|check-circle');
            } else {
                Helper::setNotify(
                    'Ops, tivemos um problema ao cancelar a edição do documento. Por favor, contate o suporte técnico!',
                    'danger|close-circle'
                );
            }
        }
        return redirect()->route('portal.processo.listarDocumentos', ['idRegistro' => $documentoCompleto->idRegistro]);
    }
}
