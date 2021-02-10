<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Classes\Helper;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Core\Repositories\{ParametroRepository, SetorRepository};
use Modules\Docs\Repositories\{OpcaoControleRegistroRepository, ControleRegistroRepository};
use Modules\Docs\Services\ControleRegistroService;

class ControleRegistroController extends Controller
{
    protected $controleRegistroRepository;
    protected $opcoesControleRegistroRepository;
    protected $setorRepository;
    protected $parametroRepository;

    public function __construct()
    {
        $this->controleRegistroRepository = new ControleRegistroRepository();
        $this->opcoesControleRegistroRepository = new OpcaoControleRegistroRepository();
        $this->setorRepository = new SetorRepository();
        $this->parametroRepository = new ParametroRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $controles = $this->controleRegistroRepository->findAll(); 
        return view('docs::controle-registro.index', compact('controles'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $idSetorQualidade = $this->parametroRepository->getParametro('ID_SETOR_QUALIDADE');
        $responsaveis = $this->setorRepository->getSetorUsuario($idSetorQualidade) ?? [];

        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        $meiosArmazenamento       = $this->getOption('LOCAL_ARMAZENAMENTO');
        $disposicoes              = $this->getOption('DISPOSICAO');
        $meios                    = $this->getOption('MEIO_DISTRIBUICAO');
        $meiosProtecao            = $this->getOption('MEIO_PROTECAO');
        $meiosRecuperacao         = $this->getOption('RECUPERACAO');
        $opcoesRetencaoDeposito   = $this->getOption('TEMPO_RETENCAO_DEPOSITO');
        $opcoesRetencaoLocal      = $this->getOption('TEMPO_RETENCAO_LOCAL');

        return view('docs::controle-registro.create',
            [
                'responsaveis'           => $responsaveis,
                'meiosArmazenamento'     => $meiosArmazenamento,
                'niveisAcesso'           => $niveisAcesso,
                'disposicoes'            => $disposicoes,
                'meios'                  => $meios,
                'meiosProtecao'          => $meiosProtecao,
                'meiosRecuperacao'       => $meiosRecuperacao,
                'opcoesRetencaoDeposito' => $opcoesRetencaoDeposito,
                'opcoesRetencaoLocal'    => $opcoesRetencaoLocal
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $controleRegistroService = new ControleRegistroService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $controleRegistroService->store($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Novo controle de registro cadastrado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.controle-registro');
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
        $controleRegistro = $this->controleRegistroRepository->find($id);
        $idSetorQualidade = $this->parametroRepository->getParametro('ID_SETOR_QUALIDADE');
        $responsaveis = $this->setorRepository->getSetorUsuario($idSetorQualidade);

        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        $meiosArmazenamento       = $this->getOption('LOCAL_ARMAZENAMENTO');
        $disposicoes              = $this->getOption('DISPOSICAO');
        $meios                    = $this->getOption('MEIO_DISTRIBUICAO');
        $meiosProtecao            = $this->getOption('MEIO_PROTECAO');
        $meiosRecuperacao         = $this->getOption('RECUPERACAO');
        $opcoesRetencaoDeposito   = $this->getOption('TEMPO_RETENCAO_DEPOSITO');
        $opcoesRetencaoLocal      = $this->getOption('TEMPO_RETENCAO_LOCAL');

        return view('docs::controle-registro.edit',
            [
                'controleRegistro'       => $controleRegistro,
                'responsaveis'           => $responsaveis,
                'meiosArmazenamento'     => $meiosArmazenamento,
                'niveisAcesso'           => $niveisAcesso,
                'disposicoes'            => $disposicoes,
                'meios'                  => $meios,
                'meiosProtecao'          => $meiosProtecao,
                'meiosRecuperacao'       => $meiosRecuperacao,
                'opcoesRetencaoDeposito' => $opcoesRetencaoDeposito,
                'opcoesRetencaoLocal'    => $opcoesRetencaoLocal
            ]
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
        $controleRegistroService = new ControleRegistroService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $controleRegistroService->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Controle de registro atualizada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.controle-registro');
        }
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
                $this->controleRegistroRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function validador(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'codigo'          => empty($request->get('idControleRegistro')) ? 'required|string|unique:docs_controle_registros,codigo' : 'required|string|unique:docs_controle_registros,codigo,' . $request->idControleRegistro,
                'descricao'       => 'required|string|min:5|max:100',
                'responsavel'     => 'required|numeric',
                'meio'            => 'required|numeric',
                'armazenamento'   => 'required|numeric',
                'protecao'        => 'required|numeric',
                'recuperacao'     => 'required|numeric',
                'nivelAcesso'     => 'required|numeric',
                'retencaoLocal'   => 'required|numeric',
                'retencaoDeposito' => 'required|numeric',
                'disposicao'      => 'required|numeric'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public function montaRequest(Request $request)
    {
        $retorno = [
            "codigo"                      => $request->get('codigo'),
            "titulo"                      => $request->get('descricao'),
            "nivel_acesso_id"             => $request->get('nivelAcesso'),
            "avulso"                      => true,
            "documento_id"                => null,
            "setor_id"                    => $request->get('responsavel'),
            "local_armazenamento_id"      => $request->get('armazenamento'),
            "disposicao_id"               => $request->get('disposicao'),
            "meio_distribuicao_id"        => $request->get('meio'),
            "protecao_id"                 => $request->get('protecao'),
            "recuperacao_id"              => $request->get('recuperacao'),
            "tempo_retencao_deposito_id"  => $request->get('retencaoDeposito'),
            "tempo_retencao_local_id"     => $request->get('retencaoLocal'),
            "ativo"                       => $request->get('ativo') == 1 ? true : false,
        ];

        if ($request->idControleRegistro) {
            $retorno['id'] = $request->idControleRegistro;
        }
        return $retorno;
    }

    private function getOption($_key)
    {
        $opcao = $this->opcoesControleRegistroRepository->findBy(
            [
                ['campo_id','=', $_key]
            ],
            [],
            [
                ['descricao', 'ASC']
            ]
        );

        return array_column(json_decode($opcao), 'descricao', 'id');
    }
}
