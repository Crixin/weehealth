<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\DocumentoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\SetorRepository;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;
use Modules\Docs\Repositories\DocumentoItemNormaRepository;
use Modules\Docs\Repositories\DocumentoPaiRepository;
use Modules\Docs\Repositories\DocumentoVinculadoRepository;
use Modules\Docs\Repositories\NormaRepository;
use Modules\Docs\Repositories\TipoDocumentoRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;
use Modules\Docs\Services\DocumentoService;
use Modules\Docs\Services\TipoDocumentoService;

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
    protected $documentoVinculadoRepository;
    protected $documentoPairepository;

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
        DocumentoVinculadoRepository $documentoVinculadoRepository,
        DocumentoPaiRepository $documentoPaiRepository
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
        $this->documentoVinculadoRepository = $documentoVinculadoRepository;
        $this->documentoPairepository = $documentoPaiRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $documentos = $this->documentoRepository->findAll();
        return view('docs::documento.index', compact('documentos'));
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
        foreach ($setores as $key => $setor) {
            $arrUsers = [];
            $users = $this->userRepository->findBy(
                [
                    ['setor_id', '=', $setor->id]
                ]
            );
            foreach ($users as $key => $user) {
                $arrUsers[$user->id] = $user->name;
            }
            $setoresUsuarios[$setor->nome] = $arrUsers;
        }
        $setores = array_column(json_decode(json_encode($setores), true), 'nome', 'id');

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

        return view('docs::documento.create',
            compact(
                'documentos',
                'setores',
                'tiposDocumento',
                'niveisAcesso',
                'classificacoes',
                'setoresUsuarios',
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
        $cadastro = $this->montaRequest($request);
        
        try {
            DB::transaction(function () use ($cadastro, $request) {
                $criarDocumento = new DocumentoService();
                $documentoCriado = $criarDocumento->create($cadastro);

                /**Documentos Pai */
                if (!empty($request->documentoPai)) {
                    foreach (json_decode($request->documentoPai) as $key => $valueDocumentoPai) {
                        $montaRequestDocumentoPai = [
                            'documento_id'     =>  $documentoCriado->id,
                            'documento_pai_id' => $valueDocumentoPai
                        ];
                        $this->documentoPairepository->create($montaRequestDocumentoPai);
                    }
                }

                /**Documentos Vinculados */
                if (!empty($request->documentoVinculado)) {
                    foreach (json_decode($request->documentoVinculado) as $key => $valueDocumentosVinculados) {
                        $montaRequestDocumentosVinculados = [
                            'documento_id'           =>  $documentoCriado->id,
                            'documento_vinculado_id' => $valueDocumentosVinculados
                        ];
                        $this->documentoVinculadoRepository->create($montaRequestDocumentosVinculados);
                    }
                }

                /**Grupo Treinamento */
                if (!empty($request->grupoTreinamentoDoc)) {
                    foreach (json_decode($request->grupoTreinamentoDoc) as $key => $valueUserTreinamento) {
                        $montaRequestTreinamento = [
                            "tipo" => 'TREINAMENTO',
                            "documento_id" => $documentoCriado->id,
                            "user_id" => $valueUserTreinamento
                        ];
                        $this->agrupamentoUserDocumentoRepository->create($montaRequestTreinamento);
                    }
                }

                /**Grupo Divulgacao */
                if (!empty($request->grupoDivulgacaoDoc)) {
                    foreach (json_decode($request->grupoDivulgacaoDoc) as $key => $valueUserDivulgacao) {
                        $montaRequestDivulgacao = [
                            "tipo" => 'DIVULGACAO',
                            "documento_id" => $documentoCriado->id,
                            "user_id" => $valueUserDivulgacao
                        ];
                        $this->agrupamentoUserDocumentoRepository->create($montaRequestDivulgacao);
                    }
                }

                /**Normas */
                if (!empty($request->grupoNorma)) {
                    foreach (json_decode($request->grupoNorma) as $key => $valueNormas) {
                        $montaRequestNorma = [
                            "documento_id" => $documentoCriado->id,
                            "item_norma_id" => $valueNormas
                        ];
                        $this->documentoItemNormaRepository->create($montaRequestNorma);
                    }
                }

                /**Etapas de Aprovacao */
                $tipoDocumentoService = new TipoDocumentoService();
                $etapas = $tipoDocumentoService->getEtapasFluxosPorComportamento(
                    $request->tipoDocumento,
                    'comportamento_aprovacao'
                );
                foreach ($etapas as $key => $value) {
                    $variavel = 'grupo' . $value['nome'];
                    if (!empty($request->$variavel)) {
                        foreach (json_decode($request->$variavel) as $key => $idAprovadores) {
                            $montaRequestEtapa = [
                                "user_id" => $idAprovadores,
                                "etapa_id" => $value['id'],
                                "documento_id" => $documentoCriado->id
                            ];
                            $this->userEtapaDocumentoRepository->create($montaRequestEtapa);
                        }
                    }
                }
            });
            Helper::setNotify('Novo documento criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao gravar o documento', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
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
        return view('docs::documento.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $id = $request->get('idDocumento');

        $update  = $this->montaRequest($request, $id);
        try {
            DB::transaction(function () use ($update, $id) {
                $this->documentoRepository->update($update, $id);
            });

            Helper::setNotify('Informações do documento atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao atualizar o documento', 'danger|close-circle');
        }
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

    public function importarDocumento(Request $request)
    {
        $error = $this->validador($request);
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

        $documentoService = new DocumentoService();
        $codigo = $documentoService::gerarCodigoDocumento($request->tipoDocumento, $buscaSetores->id);

        return view('docs::documento.import',
            [
                'titulo'          => $request->tituloDocumento,
                'nivelAcesso'     => $niveisAcesso[$request->nivelAcesso],
                'setor'           => $buscaSetores->nome,
                'copiaControlada' => $request->copiaControlada == 1 ? 'Sim' : 'Não',
                'classificacao'   => $classificacoes[$request->classificacao],
                'tipoDocumento'   => $buscaTipoDocumento->nome,
                'validade'        => $buscaTipoDocumento->periodo_vigencia_id . " Meses",
                'codigo'          => $codigo,
                'request'         => $request
            ]
        );
    }

    public function criarDocumento(Request $request)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
 
        $docPath = '';
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

        $documentoService = new DocumentoService();
        $codigo = $documentoService::gerarCodigoDocumento($request->tipoDocumento, $buscaSetores->id);

        return view('docs::documento.factoryDoc',
            [
                'titulo'          => $request->tituloDocumento,
                'nivelAcesso'     => $niveisAcesso[$request->nivelAcesso],
                'setor'           => $buscaSetores->nome,
                'copiaControlada' => $request->copiaControlada == 1 ? 'Sim' : 'Não',
                'classificacao'   => $classificacoes[$request->classificacao],
                'tipoDocumento'   => $buscaTipoDocumento->nome,
                'validade'        => $buscaTipoDocumento->periodo_vigencia_id . " Meses",
                'codigo'          => $codigo,
                'docPath'         => $docPath,
                'request'         => $request
            ]
        );
    }

    public function validador(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tituloDocumento'    => 'required|string|min:5|max:100',
                'setor'              => 'required|numeric',
                'tipoDocumento'      => 'required|numeric',
                'nivelAcesso'        => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }

    public function montaRequest(Request $request)
    {
        return [
            "nome"                           => $request->get('tituloDocumento'),
            "codigo"                         => $request->get('codigoDocumento'),
            "validade"                       => date('Y-m-d'),
            "observacao"                     => "Criação do Documento",
            "tipo_documento_id"              => (int) $request->get('tipoDocumento'),
            "status"                         => true,
            "copia_controlada"               => $request->get('copiaControlada') == 1 ? true : false,
            "nivel_acesso"                   => (int) $request->get('nivelAcesso'),
            "setor_id"                       => (int) $request->get('setor'),
            "obsoleto"                       => $request->get('obsoleto') == 1 ? true : false,
            "elaborador_id"                  => Auth::user()->id,
            "aprovador_id"                   => Auth::user()->id,
            "classificacao"                  => (int) $request->classificacao,
            "necessita_revisao"              => false,
            "aprovador_id"                   => null,
            "justificativa_rejeicao_revisao" => null,
            "em_revisao"                     => false,
            "justificativa_cancelar_revisao" => null,
            "finalizado"                     => true,
            "revisao_curta"                  => false,
            "tipo"                           => '',
            "extensao"                       => ''
        ];
    }
}
