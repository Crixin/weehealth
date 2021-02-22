<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\CopiaControladaRepository;
use Modules\Docs\Services\CopiaControladaService;

class CopiaControladaController extends Controller
{

    protected $copiaControladaRepository;

    public function __construct()
    {
        $copiaControladaRepository = new CopiaControladaRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('docs::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('docs::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $copiaControlada = new CopiaControladaService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $copiaControlada->store($montaRequest);
        dd($reponse);
        if (!$reponse['success']) {
            Helper::setNotify('Um erro ocorreu ao gravar o registro de cópia controlada', 'danger|close-circle');
            return false;
        } else {
            Helper::setNotify('Novo registro de cópia controlada criada com sucesso!', 'success|check-circle');
            return true;
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
        return view('docs::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
                $copiaControlada = new CopiaControladaService();
                $copiaControlada->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function montaRequest(Request $request)
    {
        return [
            "documento_id" => $request->idDocumento,
            "user_id" => $request->responsavel,
            "numero_copias" => $request->numeroDeCopias,
            "revisao" => $request->revisaoDasCopias,
            "setor" => $request->setorDasCopias
        ];
    }

    public function getCopiaControlada(Request $request)
    {
        try {
            $copiaControladaRepository = new CopiaControladaRepository();
            $buscaCopiaControlada = $copiaControladaRepository->findBy(
                [
                    ['documento_id', '=', $request->documento_id]
                ]
            );

            $copiaControlada = [];
            foreach ($buscaCopiaControlada as $key => $copia) {
                $copiaControlada[] = [
                    "documento_id" => $copia->documento_id,
                    "user" => $copia->coreUsers->name,
                    "numero_copias" => $copia->numero_copias,
                    "revisao" => $copia->revisao,
                    "setor" => $copia->setor,
                    "id" => $copia->id
                ];
            }
            return response()->json(['response' => 'sucesso', 'data' => $copiaControlada]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }
}
