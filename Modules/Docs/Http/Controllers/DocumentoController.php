<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\{Auth, DB, Storage};
use Modules\Docs\Repositories\{
    DocumentoRepository,
    AgrupamentoUserDocumentoRepository,
    BpmnRepository,
    DocumentoItemNormaRepository,
    HierarquiaDocumentoRepository,
    ListaPresencaRepository,
    NormaRepository,
    TipoDocumentoRepository,
    UserEtapaDocumentoRepository,
    VinculoDocumentoRepository,
    WorkflowRepository,
    EtapaFluxoRepository,
    HistoricoDocumentoRepository
};
use Modules\Core\Repositories\{GrupoRepository, ParametroRepository, SetorRepository, UserRepository};
use Modules\Docs\Services\{DocumentoService, RegistroImpressoesService, TipoDocumentoService, WorkflowService};

class DocumentoController extends Controller
{
    protected $documentoRepository;
    protected $setorRepository;
    protected $userRepository;
    protected $normaRepositorty;
    protected $tipoDocumentoRepository;
    protected $parametroRepository;
    protected $userEtapaDocumentoRepository;
    protected $documentoItemNormaRepository;
    protected $agrupamentoUserDocumentoRepository;
    protected $vinculoDocumentoRepository;
    protected $hierarquiaDocumentorepository;
    protected $workflowRepository;
    protected $listaPresencaRepository;
    protected $grupoRepository;
    protected $registroImpressoesService;
    protected $etapaFluxoRepository;
    protected $bpmnRepository;
    protected $historicoDocumentoRepository;

    protected $documentoService;
    protected $tipoDocumentoService;
    protected $workflowService;


    public function __construct() {
        $this->documentoRepository = new DocumentoRepository();
        $this->setorRepository = new SetorRepository();
        $this->userRepository = new UserRepository();
        $this->normaRepositorty = new NormaRepository();
        $this->tipoDocumentoRepository = new TipoDocumentoRepository();
        $this->parametroRepository = new ParametroRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->documentoItemNormaRepository = new DocumentoItemNormaRepository();
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
        $this->vinculoDocumentoRepository = new VinculoDocumentoRepository();
        $this->hierarquiaDocumentorepository = new HierarquiaDocumentoRepository();
        $this->workflowRepository = new WorkflowRepository();
        $this->grupoRepository = new GrupoRepository();
        $this->listaPresencaRepository = new ListaPresencaRepository();
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
        $this->bpmnRepository = new BpmnRepository();
        $this->historicoDocumentoRepository = new HistoricoDocumentoRepository();

        $this->documentoService = new DocumentoService();
        $this->tipoDocumentoService = new TipoDocumentoService();
        $this->workflowService = new WorkflowService();
        $this->registroImpressoesService = new RegistroImpressoesService();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $buscaSetores = $this->setorRepository->findBy(
            [
                ['nome', '!=', 'Sem Setor']
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $setores = array_column(json_decode(json_encode($buscaSetores), true), 'nome', 'id');

         /**TIPO DOCUMENTO */
        $buscaTiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true]
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $tiposDocumento = array_column(json_decode(json_encode($buscaTiposDocumento), true), 'nome', 'id');

        /**NIVEL ACESSO*/
        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        /**STATUS */
        $statusEtapa = $this->parametroRepository->getParametro('STATUS_ETAPA_FLUXO');
        $status = json_decode($statusEtapa);

        $opcoesVencimento = ["hoje" => "Hoje", "mes" => "Mês", "mespassado" => "Mês Passado", "definir" => "Definir Período"];

        /**FILTROS */
        $where = [];
        if ($request->titulo) {
            array_push($where, ['nome','like','%' . $request->titulo . '%']);
        }

        if ($request->setor) {
            array_push($where, ['setor_id','',$request->setor, "IN"]);
        }

        if ($request->tipoDocumento) {
            array_push($where, ['tipo_documento_id','',$request->tipoDocumento, "IN"]);
        }

        if ($request->nivelAcesso) {
            array_push($where, ['nivel_acesso_id','',$request->nivelAcesso, "IN"]);
        }

        if ($request->tipoVencimento) {
            switch ($request->tipoVencimento) {
                case 'hoje':
                    array_push($where, ['validade','=',date('Y-m-d'), "AND"]);
                    break;
                case 'mes':
                    $datas = Helper::mesBetween(0);
                    array_push($where, ['validade','>=',$datas['dataInicial'], "AND"]);
                    array_push($where, ['validade','<=',$datas['dataFinal'], "AND"]);
                    break;
                case 'mespassado':
                    $datas = Helper::mesPassadoBetween();
                    array_push($where, ['validade','>=',$datas['dataInicial'], "AND"]);
                    array_push($where, ['validade','<=',$datas['dataFinal'], "AND"]);
                    break;
                case 'definir':
                    array_push($where, ['validade','>=',$request->dataInicial ?? '1901-01-01', "AND"]);
                    array_push($where, ['validade','<=',$request->dataFinal ?? '2100-01-01', "AND"]);
                    break;
            }
        }

        if ($request->copiaControlada != null) {
            array_push($where, ['copia_controlada','=',$request->copiaControlada == 1 ? true : false, "AND"]);
        }

        if ($request->obsoleto != null) {
            array_push($where, ['obsoleto','=',$request->obsoleto == 1 ? true : false , "AND"]);
        }

        $documentos = $this->documentoRepository->findBy($where);
        return view(
            'docs::documento.index',
            [
                'documentos' => $documentos,
                'setores' => $setores,
                'tiposDocumento' => $tiposDocumento,
                'niveisAcesso' => $niveisAcesso,
                'opcoesVencimento' => $opcoesVencimento,
                'options' => ["1" => "Sim", "0" => "Não"],
                'status' => $status,
                'tituloSelecionado' => $request->titulo ?? null,
                'setorSelecionado' => $request->setor ?? null,
                'tipoDocumentoSelecionado' => $request->tipoDocumento ?? null,
                'statusSelecionado' => $request->status ?? null,
                'niveisSelecionado' => $request->nivelAcesso ?? null,
                'opcoesSelecionado' => $request->tipoVencimento ?? [],
                'dataInicialSelecionado' => $request->dataInicial ?? null,
                'dataFinalSelecionado' => $request->dataFinal ?? null,
                'copiaControladaSelecionado' => $request->copiaControlada,
                'obsoletoSelecionado' => $request->obsoleto,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        /**Setores */
        $setores = $this->setorRepository->findBy(
            [
                ['nome', '!=', 'Sem Setor']
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $setores = array_column(json_decode(json_encode($setores), true), 'nome', 'id');

        /**Grupos */
        $grupos = $this->grupoRepository->findBy(
            [],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        foreach ($grupos as $key => $grupo) {
            $arrUsers = [];
            foreach ($grupo->coreUsers as $key => $user) {
                $arrUsers[$grupo->id . '-' . $user->id] = $user->name;
            }
            $gruposUsuarios[$grupo->nome] = $arrUsers;
        }

        /**TIPO DOCUMENTO */
        $tiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true]
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $tiposDocumento = array_column(json_decode(json_encode($tiposDocumento), true), 'nome', 'id');

        /**NIVEL ACESSO*/
        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        /**CLASSIFICACAO*/
        $buscaClassificacao = $this->parametroRepository->getParametro('CLASSIFICACAO');
        $classificacoes     = json_decode($buscaClassificacao);

        /**NORMAS */
        $normas  = $this->normaRepositorty->findBy(
            [
                ['ativo', '=', true]
            ]
        );

        /**BPMN */
        $bpmns = $this->bpmnRepository->findAll();
        $bpmns = array_column(json_decode(json_encode($bpmns), true), 'nome', 'id');

        $documentos = $this->documentoRepository->findBy(
            [],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $documentos = array_column(json_decode(json_encode($documentos), true), 'nome', 'id');

        return view('docs::documento.create',
            compact(
                'documentos',
                'setores',
                'tiposDocumento',
                'niveisAcesso',
                'classificacoes',
                'gruposUsuarios',
                'normas',
                'bpmns'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //VERIFICA SE AÇÃO EH IMPORTACAO (Copiar arquivo para o OnllyOffice)
        if ($request->acao == 'IMPORTAR') {
            $file = $request->file('doc_uploaded');
            $extensao   = $file->getClientOriginalExtension();
            $nome = $file->getClientOriginalName();

            $buscaPrefixo = $this->parametroRepository->getParametro('PREFIXO_TITULO_DOCUMENTO');
            $docPath = $request->tituloDocumento . $buscaPrefixo . '00.' . $extensao;
            Storage::disk('weecode_office')->put($docPath, file_get_contents($file));
        }

        $cadastro = $this->montaRequest($request);
        $buscaTipoDocumento = $this->tipoDocumentoRepository->find($request->get('tipoDocumento'));
        $fluxo = $buscaTipoDocumento->docsFluxo;

        return $this->documentoService->store($cadastro);

        if ($retorno) {
            return response()->json(['response' => 'sucesso', 'data' => $retorno]);
        }
        return response()->json(['response' => 'erro']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('docs::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $documento = $this->documentoRepository->find($id);

        /**Setores */
        $setores = $this->setorRepository->findBy(
            [
                ['nome', '!=', 'Sem Setor']
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $setores = array_column(json_decode(json_encode($setores), true), 'nome', 'id');

        /**Grupos */
        $grupos = $this->grupoRepository->findBy(
            [],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        foreach ($grupos as $key => $grupo) {
            $arrUsers = [];
            foreach ($grupo->coreUsers as $key => $user) {
                $arrUsers[$grupo->id . '-' . $user->id] = $user->name;
            }
            $gruposUsuarios[$grupo->nome] = $arrUsers;
        }

        /**TIPO DOCUMENTO */
        $tiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true]
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $tiposDocumento = array_column(json_decode(json_encode($tiposDocumento), true), 'nome', 'id');

        /**NIVEL ACESSO*/
        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        /**CLASSIFICACAO*/
        $buscaClassificacao = $this->parametroRepository->getParametro('CLASSIFICACAO');
        $classificacoes     = json_decode($buscaClassificacao);

        /**NORMAS */
        $normas  = $this->normaRepositorty->findBy(
            [
                ['ativo', '=', true]
            ]
        );

        /**BPMN */
        $bpmns = $this->bpmnRepository->findAll();
        $bpmns = array_column(json_decode(json_encode($bpmns), true), 'nome', 'id');

        $documentos = $this->documentoRepository->findBy(
            [
                ['codigo', '!=', $documento->codigo]
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $documentos = array_column(json_decode(json_encode($documentos), true), 'nome', 'id');


        $buscaVinculadosSelecionados = $this->vinculoDocumentoRepository->findBy(
            [
                ['documento_id', '=', $id]
            ]
        );
        $documentosVinculadosSelecionados = array_column(json_decode(json_encode($buscaVinculadosSelecionados), true), 'documento_vinculado_id');

        $buscaNormasSelecionadas = $this->documentoItemNormaRepository->findBy(
            [
                ['documento_id', '=', $id]
            ]
        );
        $normasSelecionados = array_column(json_decode(json_encode($buscaNormasSelecionadas), true), 'item_norma_id');

        $buscaGrupoTreinamentoSelecionado = $this->agrupamentoUserDocumentoRepository->findBy(
            [
                ['documento_id', '=', $id],
                ['tipo', '=', 'TREINAMENTO', 'AND']
            ]
        );

        $arrayGrupoTreinamento = [];
        foreach ($buscaGrupoTreinamentoSelecionado as $key => $value) {
            array_push($arrayGrupoTreinamento, $value->grupo_id . '-' . $value->user_id);
        }
        $grupoTreinamentoSelecionado = json_decode(json_encode($arrayGrupoTreinamento), true);

        $buscaGrupoDivulgacaoSelecionado = $this->agrupamentoUserDocumentoRepository->findBy(
            [
                ['documento_id', '=', $id],
                ['tipo', '=', 'DIVULGACAO', 'AND']
            ]
        );

        $arrayGrupoDivulgacao = [];
        foreach ($buscaGrupoDivulgacaoSelecionado as $key => $value) {
            array_push($arrayGrupoDivulgacao, $value->grupo_id . '-' . $value->user_id);
        }
        $grupoDivulgacaoSelecionado = json_decode(json_encode($arrayGrupoDivulgacao), true);

        return view(
            'docs::documento.edit',
            compact(
                'documento',
                'documentos',
                'setores',
                'tiposDocumento',
                'niveisAcesso',
                'classificacoes',
                'gruposUsuarios',
                'normas',
                'bpmns',
                'documentosVinculadosSelecionados',
                'normasSelecionados',
                'grupoTreinamentoSelecionado',
                'grupoDivulgacaoSelecionado'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $update = $this->montaRequest($request);
        $id = $request->get('idDocumento');
        $retorno = $this->documentoService->update($update, $id);
        if ($retorno) {
            Helper::setNotify('Informações do documento atualizadas com sucesso!', 'success|check-circle');
            return redirect()->back()->withInput();
        }

        Helper::setNotify("Um erro ocorreu ao atualizar o documento. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request = $request->id;
        try {
            DB::transaction(function () use ($id) {
                $this->documentoRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function proximaEtapa(Request $request)
    {
        dd('proxima etapa');
        $idDocumento = $request->idDocumento;
    }


    public function importarDocumento(Request $request)
    {
        $error = $this->documentoService->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = (array) json_decode($buscaNivelAcesso);

        $buscaClassificacao = $this->parametroRepository->getParametro('CLASSIFICACAO');
        $classificacoes     = (array) json_decode($buscaClassificacao);

        $buscaSetores = $this->setorRepository->findOneBy(
            [
                ['nome', '!=', 'Sem Setor'],
                ['id', '=', $request->setor,'AND']
            ]
        );

        $buscaTipoDocumento = $this->tipoDocumentoRepository->findOneBy(
            [
                ['id', '=', $request->tipoDocumento]
            ]
        );
        
        $codigo = $this->documentoService->gerarCodigoDocumento($request->tipoDocumento, $buscaSetores->id);
        
        return view(
            'docs::documento.import',
            [
                'titulo'          => $request->tituloDocumento,
                'nivelAcesso'     => $niveisAcesso[$request->nivelAcesso],
                'setor'           => $buscaSetores->nome,
                'copiaControlada' => $request->copiaControlada == 1 ? 'Sim' : 'Não',
                'classificacao'   => $classificacoes[$request->classificacao] ?? null,
                'tipoDocumento'   => $buscaTipoDocumento->nome,
                'validade'        => $buscaTipoDocumento->periodo_vigencia . " Meses",
                'codigo'          => $codigo,
                'request'         => $request,
                'permissaoEtapa'  => $buscaTipoDocumento->docsFluxo->docsEtapaFluxo[0]
            ]
        );
    }

    public function criarDocumento(Request $request)
    {
        $error = $this->documentoService->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = (array) json_decode($buscaNivelAcesso);

        $buscaClassificacao = $this->parametroRepository->getParametro('CLASSIFICACAO');
        $classificacoes     = (array) json_decode($buscaClassificacao);

        $buscaSetores = $this->setorRepository->findOneBy(
            [
                ['nome', '!=', 'Sem Setor'],
                ['id', '=', $request->setor,'AND']
            ]
        );

        $buscaTipoDocumento = $this->tipoDocumentoRepository->findOneBy(
            [
                ['id', '=', $request->tipoDocumento]
            ]
        );

        $tipoArquivo = substr($buscaTipoDocumento->modelo_documento, 11, strpos($buscaTipoDocumento->modelo_documento, ';') - 11);
        $buscaPrefixo = $this->parametroRepository->getParametro('PREFIXO_TITULO_DOCUMENTO');

        $codigo = $this->documentoService->gerarCodigoDocumento($request->tipoDocumento, $buscaSetores->id);
        $docPath = $request->tituloDocumento . $buscaPrefixo . '00.' . ($tipoArquivo == 'ation/vnd.ms-excel' ? 'xlsx' : 'docx');

        /**SALVAR NA PASTA DO ONLYOFFICE */
        $storagePath = Storage::disk('weecode_office')->getDriver()->getAdapter()->getPathPrefix();

        Helper::base64ToImage($buscaTipoDocumento->modelo_documento, $storagePath . $docPath);

        return view(
            'docs::documento.factory',
            [
                'titulo'          => $request->tituloDocumento,
                'nivelAcesso'     => $niveisAcesso[$request->nivelAcesso],
                'setor'           => $buscaSetores->nome,
                'copiaControlada' => $request->copiaControlada == 1 ? 'Sim' : 'Não',
                'classificacao'   => $classificacoes[$request->classificacao] ?? null,
                'tipoDocumento'   => $buscaTipoDocumento->nome,
                'validade'        => $buscaTipoDocumento->periodo_vigencia . " Meses",
                'codigo'          => $codigo,
                'request'         => $request,
                'docPath'         => $docPath,
                'permissaoEtapa'  => $buscaTipoDocumento->docsFluxo->docsEtapaFluxo[0]
            ]
        );
    }

    public function montaRequest(Request $request)
    {
        #Hierarquia Documentos
        $montaRequestHierarquiaDocumento = [];
        if (!empty($request->documentoPai)) {
            $foreach = !is_array($request->documentoPai) ? json_decode($request->documentoPai) : $request->documentoPai;
            foreach ($foreach as $key => $valueDocumentoPai) {
                $montaRequestHierarquiaDocumento[$key] = [
                    'documento_pai_id' => (int) $valueDocumentoPai
                ];
            }
        }

        #Documentos Vinculados
        $montaRequestVinculoDocumento = [];
        if (!empty($request->documentoVinculado)) {
            $foreach = !is_array($request->documentoVinculado) ? json_decode($request->documentoVinculado) : $request->documentoVinculado;
            foreach ($foreach as $key => $valueDocumentosVinculados) {
                $montaRequestVinculoDocumento[$key] = [
                    'documento_vinculado_id' => (int) $valueDocumentosVinculados
                ];
            }
        }

        #Grupo Treinamento
        $montaRequestTreinamento = [];
        if (!empty($request->grupoTreinamentoDoc)) {
            $foreach = !is_array($request->grupoTreinamentoDoc) ? json_decode($request->grupoTreinamentoDoc) : $request->grupoTreinamentoDoc;
            foreach ($foreach as $key => $valueUserTreinamento) {
                $aux = explode('-', $valueUserTreinamento);
                $montaRequestTreinamento[$key] = [
                    "user_id" => (int) $aux[1],
                    "tipo"    => 'TREINAMENTO',
                    'grupo_id' => (int) $aux[0]
                ];
            }
        }

        #Grupo Divulgacao
        $montaRequestDivulgacao = [];
        if (!empty($request->grupoDivulgacaoDoc)) {
            $foreach = !is_array($request->grupoDivulgacaoDoc) ? json_decode($request->grupoDivulgacaoDoc) : $request->grupoDivulgacaoDoc;
            foreach ($foreach as $key => $valueUserDivulgacao) {
                $aux = explode('-', $valueUserDivulgacao);
                $montaRequestDivulgacao[$key] = [
                    "user_id" => (int) $aux[1],
                    "tipo"    => 'DIVULGACAO',
                    'grupo_id' => (int) $aux[0]
                ];
            }
        }

        #Normas
        $montaRequestNorma = [];
        if (!empty($request->grupoNorma)) {
            $foreach = !is_array($request->grupoNorma) ? json_decode($request->grupoNorma) : $request->grupoNorma;
            foreach ($foreach as $key => $valueNormas) {
                $montaRequestNorma[$key] = [
                    "item_norma_id" => (int) $valueNormas
                ];
            }
        }

        #Etapas de Aprovacao
        $montaRequestEtapa = [];
        $etapas = $this->tipoDocumentoService->getEtapasFluxosPorComportamento(
            $request->tipoDocumento,
            'comportamento_aprovacao'
        );

        foreach ($etapas['etapas'] as $etapa) {
            $variavel = 'grupo' . $etapa['id'];

            if (!empty($request->$variavel)) {
                $gruposUsersEtapas = !is_array($request->$variavel) ? json_decode($request->$variavel) : $request->$variavel;

                foreach ($gruposUsersEtapas as $grupoUserEtapa) {
                    [$grupo, $user] = explode('-', $grupoUserEtapa);
                    
                    $montaRequestEtapa["grupo_user_etapa"][] = [
                        'grupo_id' => (int) $grupo,
                        "user_id" => (int) $user,
                        "etapa_fluxo_id" => $etapa['id']
                    ];
                }
            }
        }

        //PEGA A EXTENSAO DO MODELO DO TIPO DE DOCUMENTO CASO O USER NÃO SUBIU UM ARQUIVO
        $tipoDoc = $this->tipoDocumentoRepository->find($request->tipoDocumento);
        $extensao = $tipoDoc->extensao;

        if ($request->file('doc_uploaded')) {
            $file = $request->file('doc_uploaded');
            $extensao = $file->getClientOriginalExtension();
        }

        return [
            "nome"                           => $request->get('tituloDocumento'),
            "codigo"                         => $request->get('codigoDocumento'),
            "validade"                       => null,
            "extensao"                       => $extensao,
            "tipo_documento_id"              => (int) $request->get('tipoDocumento') ?? null,
            "copia_controlada"               => $request->get('copiaControlada') == 1 ? true : false,
            "nivel_acesso_id"                => (int) $request->get('nivelAcesso') ?? null,
            "setor_id"                       => (int) $request->get('setor') ?? null,
            "obsoleto"                       => $request->get('obsoleto') == 1 ? true : false,
            "elaborador_id"                  => Auth::user()->id ?? null,
            "classificacao_id"               => (int) $request->classificacao ?? null,
            "ged_registro_id"                => null,
            "bpmn_id"                        => $request->get('bpmn') ? (int) $request->get('bpmn') : null,
            "revisao"                        => $request->idDocumento ? $request->revisao : '00',
            "hierarquia_documento"           => $montaRequestHierarquiaDocumento,
            "vinculo_documento"              => $montaRequestVinculoDocumento,
            "grupo_treinamento"              => $montaRequestTreinamento,
            "grupo_divulgacao"               => $montaRequestDivulgacao,
            "item_normas"                    => $montaRequestNorma,
            "etapa_aprovacao"                => $montaRequestEtapa

        ];
    }

    public function buscaDocumentoPaiPorTipo(Request $request)
    {
        $idDocumento = $request->documento;
        try {
            $buscaDocHierarquiaSelecionado = $this->hierarquiaDocumentorepository->findBy(
                [
                    ['documento_id', '=', $idDocumento]
                ]
            );
            $docSelecionado = array_column(json_decode(json_encode($buscaDocHierarquiaSelecionado), true), 'documento_pai_id');
            $buscaTodosDocumento = $this->documentoRepository->findBy(
                [
                    ['tipo_documento_id', '=', $request->tipo]
                ],
                [],
                [
                    ['nome', 'ASC']
                ]
            );
            $retorno = [];
            foreach ($buscaTodosDocumento as $key => $value) {
                $aux = [
                    "id" => $value->id,
                    "nome" => $value->nome,
                    "select" => in_array($value->id, $docSelecionado) ? true : false
                ];
                array_push($retorno, $aux);
            }

            return response()->json(['response' => 'sucesso', 'data' => $retorno]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function iniciarValidacao(Request $request)
    {

    }

    public function iniciarRevisao(Request $request)
    {
        $documentoService = new DocumentoService();

        $data = [
            'documento_id' => $request->documento
        ];

        if (!$documentoService->iniciarRevisao($data)['success']) {
            return redirect()->route('docs.documento');
        }
        
        return redirect()->route('docs.documento');
        //return redirect()->route('docs.documento.visualizar');
    }

    public function tornarObsoleto(Request $request)
    {

    }

    public function listaPresenca($id)
    {
        $documento = $this->documentoRepository->find($id);
        $listaPresenca = $this->listaPresencaRepository->findBy(
            [
                ['documento_id', '=', $id]
            ]
        );
        return view('docs::documento.presence-list', compact('documento', 'listaPresenca'));
    }

    public function imprimir($id, $tipo)
    {
        try {
            /** Cria registro de Intensão de impressão do documento */
            $mode           = $tipo == 2 ? "with_stripe" : 'without_stripe';
            $message        = "Sucesso! O documento foi atualizado com sucesso e as tarjas para impressão foram aplicadas.";
            $messageClass   = "success";
            $filename       = '';
            $documento      = $this->documentoRepository->find($id);
            $setorQualidade = $this->parametroRepository->getParametro('ID_SETOR_QUALIDADE');
            $historico = $this->workflowRepository->findBy(
                [
                    ['documento_id', '=', $id],
                    ['documento_revisao', '=', $documento->revisao]
                ],
                [],
                [
                    ['created_at', 'ASC']
                ]
            );

            //O SISTEMA ESTA DUPLICADO A GRAVACAO NA TABELA DE IMPRESSOES BUG 
            //$this->registroImpressoesService->create(['documento_id' => $id, 'user_id' => Auth::user()->id]);

            return view('docs::documento.print', compact('mode', 'documento', 'setorQualidade', 'message', 'messageClass', 'filename', 'historico'));
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao tentar imprimir o documento', 'danger|close-circle');
            return redirect()->route('docs.documento');
        }
    }


    public function visualizar($id)
    {
        $documento = $this->documentoRepository->find($id);


        $historico = $this->linhaTempo($documento->id);
      
        $workflow = $this->workflowRepository->findBy(
            [
                ['documento_id', '=', $id],
                ['documento_revisao', '=', $documento->revisao],
            ],
            ['coreUsers'],
            [
                ['created_at', 'ASC']
            ]
        );

        $etapaAtual = $this->workflowService->getEtapaAtual($documento->id);

        $proximaEtapa = $this->workflowService->getProximaEtapa($documento->id);

        $revisoes  = Helper::getListAllReviewsDocument($documento->nome);

        $buscaPrefixo = $this->parametroRepository->getParametro('PREFIXO_TITULO_DOCUMENTO');

        $extensoesPermitidas = implode(", ", json_decode(Helper::buscaParametro('EXTENSAO_DOCUMENTO_ONLYOFFICE')));
        $docPath = $documento->nome . $buscaPrefixo . $documento->revisao . "." . $documento->extensao;
        
        $workflow = $workflow->toArray();
        $workflow = end($workflow);

        return view(
            'docs::documento.view',
            compact(
                'id',
                'documento',
                'historico',
                'revisoes',
                'docPath',
                'etapaAtual',
                'proximaEtapa',
                'extensoesPermitidas',
                'workflow',
            )
        );
    }

    public function buscaDocumentoPorTipo(Request $request)
    {
        try {
            $idTipo = $request->tipo;
            $documentos = $this->documentoRepository->findBy(
                [
                    ['tipo_documento_id', '=', $idTipo]
                ],
                [],
                [
                    ['id', 'DESC']
                ]
            );
            return response()->json(['response' => 'sucesso', 'data' => $documentos]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function buscaDocumentoPorGrupo(Request $request)
    {
        try {
            $idGrupo = $request->grupo;
            $idUsuario = $request->usuario;
            $buscaDocumentos = $this->documentoRepository->findBy(
                [
                    ['user_id', '=', $idUsuario,'HAS', 'docsUserEtapaDocumento'],
                    ['grupo_id', '=', $idGrupo, 'HAS', 'docsUserEtapaDocumento'],
                ],
                [],
                [
                    ['id', 'DESC']
                ]
            )->toArray();

            $buscaDocumentosAgrupamento = $this->documentoRepository->findBy(
                [
                    ['user_id', '=', $idUsuario,'HAS', 'docsAgrupamentoUserDocumento'],
                    ['grupo_id', '=', $idGrupo, 'HAS', 'docsAgrupamentoUserDocumento'],
                    ['id', '', array_column($buscaDocumentos, 'id') , 'NOTIN']
                ],
                [],
                [
                    ['id', 'DESC']
                ]
            )->toArray();

            $arrayMergeDocumentos = array_merge($buscaDocumentos, $buscaDocumentosAgrupamento);
            $teste = array_column($arrayMergeDocumentos, 'codigo', 'id');
            $documentos = [];
            foreach ($teste as $key => $value) {
                $aux = [
                    'id' => $key,
                    'codigo' => $value
                ];
                array_push($documentos, $aux);
            }
            return response()->json(['response' => 'sucesso', 'data' => json_encode($documentos)]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    private function linhaTempo($documento)
    {
        $workflow = $this->workflowRepository->findBy(
            [
                ['documento_id', '=', $documento],
            ],
            ['coreUsers'],
            [
                ['created_at', 'ASC']
            ]
        );

        $historicoDocumento = $this->historicoDocumentoRepository->findBy(
            [
                ['documento_id', '=', $documento],
            ],
            ['coreUsers'],
            [
                ['created_at', 'ASC']
            ]
        );

        $historico = $workflow->merge($historicoDocumento)->sortBy('created_at');
        return $historico;
    }
}
