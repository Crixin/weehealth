<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\ControleRegistroRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Docs\Repositories\OpcaoControleRegistroRepository;

class ControleRegistroController extends Controller
{
    protected $controleRegistroRepository;
    protected $opcoesControleRegistroRepository;

    public function __construct(ControleRegistroRepository $controleRegistroRepository, OpcaoControleRegistroRepository $opcaoControleRegistroRepository)
    {
        $this->controleRegistroRepository = $controleRegistroRepository;
        $this->opcoesControleRegistroRepository = $opcaoControleRegistroRepository;
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
        return view('docs::controle-registro.create');
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
        return view('docs::controle-registro.edit');
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
            "codigo"             => $request->get('codigo'),
            "descricao"          => $request->get('descricao'),
            "nivelAcesso"        => '',
            "avulso"             => '',
            "documento_id"       => '',
            "setor_id"           => '',
            "local_armazenamento_id" => '',
            "disposicao_id"      => '',
            "meio_distribuicao_id" => '',
            "protecao_id"         => '',
            "recuperacao_id"      => '',
            "tempo_retencao_deposito_id" => '',
            "tempo_retencao_local_id" => '',
            "ativo" => '',
            "meio_distribuicao" => '',
            "local_armazenamento" => '',
            "protecao" => '',
            "recuperacao" => '',
            "tempo_retencao_deposito" => '',
            "disposicao" => '',
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
