<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\ControleRegistroRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ControleRegistroController extends Controller
{
    protected $controleRegistroRepository;

    public function __construct(ControleRegistroRepository $controleRegistroRepository)
    {
        $this->controleRegistroRepository = $controleRegistroRepository;
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
                'descricao'          => empty($request->get('idOpcaoControle')) ? 'required|string|min:5|max:100|unique:docs_opcoes_controle_registros' : '',
                'tipoControle'       => 'required|numeric',
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
            "campo"                 => $request->get('tipoControle'),
            "ativo"                 => $request->get('ativo') == 1 ? true : false,
        ];
    }
}
