<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Docs\Repositories\PlanoRepository;

class PlanoController extends Controller
{
    protected $planoRepository;

    public function __construct(PlanoRepository $planoRepository)
    {
        $this->planoRepository = $planoRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $planos = $this->planoRepository->findAll();

        return view('docs::plano.index', compact('planos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('docs::plano.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        self::validador($request);
        $cadastro = self::montaRequest($request);
        try {
            DB::transaction(function () use ($cadastro, $request) {
                $plano = $this->planoRepository->create($cadastro);
            });
            Helper::setNotify('Novo plano criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.plano');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao gravar plano.', 'danger|close-circle');
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
        $plano = $this->planoRepository->find($id);
        return view('docs::plano.edit', compact('plano'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->id;
        self::validador($request);
        $update = self::montaRequest($request);
        try {
            DB::transaction(function () use ($update, $id) {
                $plano = $this->planoRepository->update($update, $id);
            });
            Helper::setNotify('Plano alterado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.plano');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao alterar o plano.', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $_request)
    {
        try {
            DB::transaction(function () use ($_request) {
                $this->planoRepository->delete($_request->id);
            });
            Helper::setNotify('Plano excluido com sucesso!', 'success|check-circle');
            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao excluir o plano.", 'danger|close-circle');
            return response()->json(['response' => 'erro']);
        }
    }

    public function validador(Request $request)
    {
        $validator = Validator::make
        (
            $request->all(),
            [
                'nome'                  => empty($request->get('nome')) ? 'required|string|min:5|max:100|unique:docs_plano' : '',
                'status'                => 'required',
            ]
        );


        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        return true;
    }

    public function montaRequest(Request $request)
    {
        return [
            "nome" => $request->get('nome'),
            "ativo" => $request->get('status') == 'on' ? true : false
        ];
    }
}
