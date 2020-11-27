<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\FluxoRepository;
use Modules\Docs\Repositories\TipoDocumentoRepository;

class TipoDocumentoController extends Controller
{
    protected $tipoDocumentoRepository;
    protected $fluxoRepository;

    public function __construct(TipoDocumentoRepository $tipoDocumentoRepository, FluxoRepository $fluxoRepository)
    {
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
        $this->fluxoRepository = $fluxoRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $tipos = $this->tipoDocumentoRepository->findBy(
            [],
            [],
            [
                ['name', 'ASC']
            ]
        );
        return view('docs::tipo-documento.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $tipos  = $this->tipoDocumentoRepository->findAll();
        $fluxos = $this->fluxoRepository->findAll(); 

        return view('docs::tipo-documento.create', compact('tipos', 'fluxos'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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

    public function validador(Request $request)
    {

    }

    public function montaRequest(Request $request)
    {
        return [
            "nome" => $request->get('nome'),
            "sigla" => $request->get('sigla'),
            "fluxo_id" => $request->get('fluxo_id'),
            "tipo_documento_pai_id" => $request->get('tipo_documento_pai_id')
        ];
    }
}
