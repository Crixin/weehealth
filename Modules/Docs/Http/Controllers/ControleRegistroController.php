<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\ControleRegistroRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\GrupoRepository;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\SetorRepository;
use Modules\Docs\Repositories\OpcaoControleRegistroRepository;

class ControleRegistroController extends Controller
{
    protected $controleRegistroRepository;
    protected $opcoesControleRegistroRepository;
    protected $setorRepository;
    protected $parametroRepository;

    public function __construct(
        ControleRegistroRepository $controleRegistroRepository,
        OpcaoControleRegistroRepository $opcaoControleRegistroRepository,
        SetorRepository $setorRepository,
        ParametroRepository $parametroRepository
    ) {
        $this->controleRegistroRepository = $controleRegistroRepository;
        $this->opcoesControleRegistroRepository = $opcaoControleRegistroRepository;
        $this->setorRepository = $setorRepository;
        $this->parametroRepository = $parametroRepository;
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
        $responsaveis = $this->setorRepository->getSetorUsuario($idSetorQualidade);

        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        $meiosArmazenamento       = $this->getOption('LOCAL_ARMAZENAMENTO');
        $disposicoes              = $this->getOption('DISPOSICAO');
        $meios                    = $this->getOption('MEIO_DISTRIBUICAO');
        $meiosProtecao            = $this->getOption('PROTECAO');
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
        $cadastro = $this->montaRequest($request);
        //dd($cadastro);
        try {
            DB::transaction(function () use ($cadastro) {
                $this->controleRegistroRepository->create($cadastro);
            });

            Helper::setNotify('Novo controle de registro criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.controle-registro');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o controle de registro', 'danger|close-circle');
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
        $controleRegistro = $this->controleRegistroRepository->find($id);
        $idSetorQualidade = $this->parametroRepository->getParametro('ID_SETOR_QUALIDADE');
        $responsaveis = $this->setorRepository->getSetorUsuario($idSetorQualidade);

        $buscaNivelAcesso = $this->parametroRepository->getParametro('NIVEL_ACESSO');
        $niveisAcesso    = json_decode($buscaNivelAcesso);

        $meiosArmazenamento       = $this->getOption('LOCAL_ARMAZENAMENTO');
        $disposicoes              = $this->getOption('DISPOSICAO');
        $meios                    = $this->getOption('MEIO_DISTRIBUICAO');
        $meiosProtecao            = $this->getOption('PROTECAO');
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $id = $request->get('idControleregistro');
        $update  = $this->montaRequest($request);
        try {
            DB::transaction(function () use ($update, $id) {
                $this->controleRegistroRepository->update($update, $id);
            });

            Helper::setNotify('Informações do controle de registro atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o controle de registro', 'danger|close-circle');
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
                'codigo'          => empty($request->get('idControleRegistro')) ? 'required|string|unique:docs_controle_registros' : '',
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
        return [
            "codigo"                      => $request->get('codigo'),
            "titulo"                      => $request->get('descricao'),
            "nivel_acesso"                 => $request->get('nivelAcesso'),
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
    }

    private function getOption($_key)
    {
        $opcao = $this->opcoesControleRegistroRepository->findBy(
            [
                ['campo','=', $_key]
            ],
            [],
            [
                ['descricao', 'ASC']
            ]
        );
        return array_column(json_decode($opcao), 'descricao', 'id');
    }
}
