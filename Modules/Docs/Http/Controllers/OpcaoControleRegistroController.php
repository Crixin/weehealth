<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Classes\Helper;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\OpcaoControleRegistroRepository;
use Illuminate\Support\Facades\{DB, Validator};

class OpcaoControleRegistroController extends Controller
{
    protected $opcaoControleRegistroRepository;
    protected $parametroRepository;

    public function __construct(OpcaoControleRegistroRepository $opcaoControleRegistroRepository, ParametroRepository $parametroRepository)
    {
        $this->opcaoControleRegistroRepository = $opcaoControleRegistroRepository;
        $this->parametroRepository = $parametroRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $opcoes = $this->opcaoControleRegistroRepository->findAll();
        return view('docs::opcao-controle-registro.index', compact('opcoes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $buscaTipos = $this->parametroRepository->getParametro('TIPO_CONTROLE_REGISTRO');
        $tipos = json_decode($buscaTipos);
        return view('docs::opcao-controle-registro.create', compact('tipos'));
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
                $this->opcaoControleRegistroRepository->create($cadastro);
            });

            Helper::setNotify('Nova opção de controle de registro criada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.opcao-controle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar a opção de controle de registro', 'danger|close-circle');
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
        $opcao = $this->opcaoControleRegistroRepository->find($id);

        $buscaTipos = $this->parametroRepository->getParametro('TIPO_CONTROLE_REGISTRO');
        $tipos = json_decode($buscaTipos);

        return view('docs::opcao-controle-registro.edit', compact('opcao', 'tipos'));
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

        $id = $request->get('idOpcaoControle');
        $update  = $this->montaRequest($request);
        try {
            DB::transaction(function () use ($update, $id) {
                $this->opcaoControleRegistroRepository->update($update, $id);
            });

            Helper::setNotify('Informações da opção do controle de registro atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {

            Helper::setNotify('Um erro ocorreu ao atualizar a opção do controle de registro', 'danger|close-circle');
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
                $this->opcaoControleRegistroRepository->delete($id);
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
                'descricao'          => empty($request->get('idOpcaoControle')) ? 'required|string|min:5|max:100|unique:docs_opcoes_controle_registros,descricao' : 'required|string|min:5|max:100|unique:docs_opcoes_controle_registros,descricao,' . $request->idOpcaoControle,
                'tipoControle'       => 'required|string',
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
            "descricao"             => $request->get('descricao'),
            "campo_id"              => $request->get('tipoControle'),
            "ativo"                 => $request->get('ativo') == 1 ? true : false,
        ];
    }
}
