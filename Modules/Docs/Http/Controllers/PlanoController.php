<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Docs\Repositories\PlanoRepository;
use Modules\Docs\Services\PlanoService;

class PlanoController extends Controller
{
    protected $planoRepository;

    public function __construct()
    {
        $this->planoRepository = new PlanoRepository();
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
        $planoService = new PlanoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $planoService->store($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Novo plano criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.plano');
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
        $planoService = new PlanoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $planoService->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Plano atualizado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.plano');
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

    public function montaRequest(Request $request)
    {
        $retorno = [
            "nome" => $request->get('nome'),
            "ativo" => $request->get('status') == '1' ? true : false
        ];

        if ($request->id) {
            $retorno['id'] = $request->id;
        }

        return $retorno;
    }
}
