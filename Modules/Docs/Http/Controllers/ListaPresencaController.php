<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\ListaPresencaRepository;
use Modules\Docs\Services\ListaPresencaService;

class ListaPresencaController extends Controller
{
    protected $listaPresencaRepository;
    protected $documentoRepository;

    public function __construct()
    {
        $this->listaPresencaRepository = new ListaPresencaRepository();
        $this->documentoRepository = new DocumentoRepository();
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $documento = $this->documentoRepository->find($id);
        $listaPresenca = $this->listaPresencaRepository->findBy(
            [
                ['documento_id', '=', $id]
            ]
        );
        return view('docs::documento.presence-list', compact('documento', 'listaPresenca'));
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
        $create = $this->montaRequest($request);
        $listaPresencaService = new ListaPresencaService();
        $reponse = $listaPresencaService->store($create);
        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Nova lista de presença criada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento');
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
    public function destroy($id)
    {
        //
    }

    public function montaRequest(Request $request)
    {
        return [
            "documento_id" => $request->idDocumento
        ];
    }
}
