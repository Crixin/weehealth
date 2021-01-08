<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\DocumentoRepository;
use Illuminate\Support\Facades\{Auth, DB, Storage};
use App\Classes\Helper;
use Modules\Core\Repositories\GrupoRepository;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\SetorRepository;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;
use Modules\Docs\Repositories\DocumentoItemNormaRepository;
use Modules\Docs\Repositories\HierarquiaDocumentoRepository;
use Modules\Docs\Repositories\ListaPresencaRepository;
use Modules\Docs\Repositories\NormaRepository;
use Modules\Docs\Repositories\TipoDocumentoRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;
use Modules\Docs\Repositories\VinculoDocumentoRepository;
use Modules\Docs\Repositories\WorkflowRepository;
use Modules\Docs\Services\DocumentoService;
use Modules\Docs\Services\TipoDocumentoService;
use Modules\Docs\Services\WorkflowService;

use function PHPSTORM_META\map;

class DocumentoController extends Controller
{
    protected $documentoRepository;
    protected $setorRepository;
    protected $userRepository;
    protected $normaRepositorty;
    protected $tipoDocumentorepository;
    protected $parametroRepository;
    protected $userEtapaDocumentoRepository;
    protected $documentoItemNormaRepository;
    protected $agrupamentoUserDocumentoRepository;
    protected $vinculoDocumentoRepository;
    protected $hierarquiaDocumentorepository;
    protected $grupoRepository;
    protected $documentoService;
    protected $tipoDocumentoService;
    protected $workFlowService;
    protected $workFlowRepository;
    protected $listaPresencaRepository;

    public function __construct(
        DocumentoRepository $documentoRepository,
        SetorRepository $setorRepository,
        UserRepository $userRepository,
        NormaRepository $normaRepository,
        TipoDocumentoRepository $tipoDocumentoRepository,
        ParametroRepository $parametroRepository,
        UserEtapaDocumentoRepository $userEtapaDocumentoRepository,
        DocumentoItemNormaRepository $documentoItemNormaRepository,
        AgrupamentoUserDocumentoRepository $agrupamentoUserDocumentoRepository,
        VinculoDocumentoRepository $vinculoDocumentoRepository,
        HierarquiaDocumentoRepository $hierarquiaDocumentoRepository,
        WorkflowRepository $workflowRepository,
        GrupoRepository $grupoRepository,
        ListaPresencaRepository $listaPresencaRepository,
        DocumentoService $documentoService,
        TipoDocumentoService $tipoDocumentoService,
        WorkflowService $workFlowService
    ){
        $this->documentoRepository = $documentoRepository;
        $this->setorRepository = $setorRepository;
        $this->userRepository = $userRepository;
        $this->normaRepositorty = $normaRepository;
        $this->tipoDocumentorepository = $tipoDocumentoRepository;
        $this->parametroRepository = $parametroRepository;
        $this->userEtapaDocumentoRepository = $userEtapaDocumentoRepository;
        $this->documentoItemNormaRepository = $documentoItemNormaRepository;
        $this->agrupamentoUserDocumentoRepository = $agrupamentoUserDocumentoRepository;
        $this->vinculoDocumentoRepository = $vinculoDocumentoRepository;
        $this->hierarquiaDocumentorepository = $hierarquiaDocumentoRepository;
        $this->workFlowRepository = $workflowRepository;
        $this->grupoRepository = $grupoRepository;
        $this->listaPresencaRepository = $listaPresencaRepository;
        $this->documentoService = $documentoService;
        $this->tipoDocumentoService = $tipoDocumentoService;
        $this->workFlowService = $workFlowService;
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
        $buscaTiposDocumento = $this->tipoDocumentorepository->findBy(
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
        return view('docs::documento.index',
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
                'opcoesSelecionado' => $request->tipoVencimento ?? null,
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
        $tiposDocumento = $this->tipoDocumentorepository->findBy(
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
                'normas'
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
        $cadastro = $this->montaRequest($request);
        $buscaTipoDocumento = $this->tipoDocumentorepository->find($request->get('tipoDocumento'));
        $fluxo = $buscaTipoDocumento->docsFluxo;
        $retorno = $this->documentoService->create($cadastro);
        if ($retorno) {
            if ($fluxo->docsEtapaFluxo[0]->permitir_anexo == true) {
                //abre tela anexos
                return redirect()->route('docs.anexo', ['id' => $retorno]);
            } else {
                $myRequest = new \Illuminate\Http\Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add(['idDocumento' => $retorno]);
                //metodo que executa as configurações da etapa
                $retornoProximaEtapa = $this->proximaEtapa($myRequest);
                if ($retornoProximaEtapa) {
                    Helper::setNotify('Novo documento criado com sucesso!', 'success|check-circle');
                    return redirect()->route('docs.documento');
                }
            }
        }

        Helper::setNotify("Um erro ocorreu ao gravar o documento. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
        return redirect()->route('docs.documento');
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
        $tiposDocumento = $this->tipoDocumentorepository->findBy(
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


        return view('docs::documento.edit',
            compact(
                'documento',
                'documentos',
                'setores',
                'tiposDocumento',
                'niveisAcesso',
                'classificacoes',
                'gruposUsuarios',
                'normas',

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
        $idDocumento = $request->idDocumento;

        /*
        $buscaDocumento = $this->documentoRepository->find($idDocumento);

        $buscaUltimaEtapa = $this->workFlowRepository->findBy(
            [
                ['documento_id', '=', $idDocumento],
                ['versao_documento', '=', $buscaDocumento->revisao, 'AND']
            ],
            [],
            [
                ['created_at', 'DESC']
            ]
        );
        $etapaAtual = $buscaUltimaEtapa->isEmpty() ? 1 : $buscaUltimaEtapa->etapa_fluxo_id + 1;
        dd($etapaAtual);
        */

        /*
        $fluxo = $buscaDocumento->docsTipoDocumento->docsFluxo;
        if ($fluxo->docsEtapaFluxo[0]->enviar_notificacao == true) {
            $idNotificacao = $fluxo->docsEtapaFluxo[0]->notificacao_id;
            //chama servico email
        }

        //Cria notificação para todos usuários do setor Qualidade;
        //chama servico de notificacao usuario

        //Cria registro workflow
        $createWorkFlow = $this->montaRequestWorkFlow(
            'Documento em elaboração',
            '',
            false,
            $idDocumento,
            $fluxo->docsEtapaFluxo[0]->id,
            $buscaDocumento->revisao
        );
        $this->workFlowService->create($createWorkFlow);
        */

    }

    public function montaRequestWorkFlow(
        $descricao,
        $justificativa,
        $justificativaLida,
        $idDocumento,
        $etapaFluxoId,
        $versaoDocumento
    )
    {
        return [
            "descricao" => $descricao,
            "justificativa" => $justificativa,
            "justificativa_lida" => $justificativaLida,
            "documento_id" => $idDocumento,
            "etapa_fluxo_id" => $etapaFluxoId,
            "user_id" => Auth::user()->id,
            "versao_documento" => $versaoDocumento
        ];
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

        $buscaTipoDocumento = $this->tipoDocumentorepository->findOneBy(
            [
                ['id', '=', $request->tipoDocumento]
            ]
        );

        $codigo = $this->documentoService->gerarCodigoDocumento($request->tipoDocumento, $buscaSetores->id);
        
        return view('docs::documento.import',
            [
                'titulo'          => $request->tituloDocumento,
                'nivelAcesso'     => $niveisAcesso[$request->nivelAcesso],
                'setor'           => $buscaSetores->nome,
                'copiaControlada' => $request->copiaControlada == 1 ? 'Sim' : 'Não',
                'classificacao'   => $classificacoes[$request->classificacao],
                'tipoDocumento'   => $buscaTipoDocumento->nome,
                'validade'        => $buscaTipoDocumento->periodo_vigencia . " Meses",
                'codigo'          => $codigo,
                'request'         => $request
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

        $buscaTipoDocumento = $this->tipoDocumentorepository->findOneBy(
            [
                ['id', '=', $request->tipoDocumento]
            ]
        );

        $tipoArquivo = substr($buscaTipoDocumento->modelo_documento, 11, strpos($buscaTipoDocumento->modelo_documento, ';') - 11);
        $buscaPrefixo = $this->parametroRepository->getParametro('PREFIXO_TITULO_DOCUMENTO');

        $codigo = $this->documentoService->gerarCodigoDocumento($request->tipoDocumento, $buscaSetores->id);
        $docPath = $request->tituloDocumento . $buscaPrefixo . '00.' . ($tipoArquivo == 'ation/vnd.ms-excel' ? 'xlsx' : 'docx');

        /**SALVAR NA PASTA DO ONLYOFFICE */
        $storagePath = Storage::disk('speed_office')->getDriver()->getAdapter()->getPathPrefix();
        Helper::base64ToImage($buscaTipoDocumento->modelo_documento, $storagePath . $docPath);

        return view('docs::documento.factory',
            [
                'titulo'          => $request->tituloDocumento,
                'nivelAcesso'     => $niveisAcesso[$request->nivelAcesso],
                'setor'           => $buscaSetores->nome,
                'copiaControlada' => $request->copiaControlada == 1 ? 'Sim' : 'Não',
                'classificacao'   => $classificacoes[$request->classificacao],
                'tipoDocumento'   => $buscaTipoDocumento->nome,
                'validade'        => $buscaTipoDocumento->periodo_vigencia . " Meses",
                'codigo'          => $codigo,
                'request'         => $request,
                'docPath'         => $docPath,
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
        foreach ($etapas['etapas'] as $key => $value) {
            $variavel = 'grupo' . $value['id'];
            if (!empty($request->$variavel)) {
                $foreach = !is_array($request->$variavel) ? json_decode($request->$variavel) : $request->$variavel;
                foreach ($foreach as $key => $idAprovadores) {
                    $aux = explode('-', $idAprovadores);
                    $montaRequestEtapa[$key] = [
                        "user_id" => (int) $aux[1],
                        "etapa_fluxo_id" => $value['id'],
                        'grupo_id' => (int) $aux[0]
                    ];
                }
            }
        }

        return [
            "nome"                           => $request->get('tituloDocumento'),
            "codigo"                         => $request->get('codigoDocumento'),
            "validade"                       => null,
            "tipo_documento_id"              => (int) $request->get('tipoDocumento') ?? null,
            "copia_controlada"               => $request->get('copiaControlada') == 1 ? true : false,
            "nivel_acesso_id"                => (int) $request->get('nivelAcesso') ?? null,
            "setor_id"                       => (int) $request->get('setor') ?? null,
            "obsoleto"                       => $request->get('obsoleto') == 1 ? true : false,
            "elaborador_id"                  => Auth::user()->id ?? null,
            "classificacao_id"               => (int) $request->classificacao ?? null,
            "etapa_id"                       => (int) $request->setor ?? null,
            "justificativa_rejeicao_etapa"   => null,
            "justificativa_cancelar_etapa"   => null,
            "ged_documento_id"               => null,
            "revisao"                        => '1.0',
            "hierarquia_documento"           => $montaRequestHierarquiaDocumento,
            "vinculo_documento"              => $montaRequestVinculoDocumento,
            "grupo_treinamento"              => $montaRequestTreinamento,
            "grupo_divulgacao"               => $montaRequestDivulgacao,
            "item_normas"                    => $montaRequestNorma,
            "etapa_aprovacao"                => $montaRequestEtapa

        ];
    }

    public function buscaDocumentoPorTipo(Request $request)
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
        $mode           = $tipo == 2 ? "with_stripe" : 'without_stripe';
        $message        = "Sucesso! O documento foi atualizado com sucesso e as tarjas para impressão foram aplicadas.";
        $messageClass   = "success";
        $filename       = '';
        $documento      = $this->documentoRepository->find($id);
        $setorQualidade = $this->parametroRepository->getParametro('ID_SETOR_QUALIDADE');
        $historico = $this->workFlowRepository->findBy(
            [
                ['documento_id', '=', $id],
                ['versao_documento', '=', $documento->revisao]
            ],
            [],
            [
                ['created_at', 'ASC']
            ]
        );
        /** Cria registro de Intensão de impressão do documento */
        return view('docs::documento.print', compact('mode', 'documento', 'setorQualidade', 'message', 'messageClass', 'filename', 'historico'));
    }

}
