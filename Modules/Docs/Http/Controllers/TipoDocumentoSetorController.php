<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\TipoDocumentoSetorRepository;
use Modules\Docs\Services\TipoDocumentoSetorService;

class TipoDocumentoSetorController extends Controller
{

    protected $tipoDocumentoSetorRepository;
    protected $documentoRepository;


    public function __construct()
    {
        $this->tipoDocumentoSetorRepository = new TipoDocumentoSetorRepository();
        $this->documentoRepository = new DocumentoRepository();
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

    public function getSetor(Request $request)
    {
        try {
            $id = $request->id;
            $documentoId = $request->idDocumento;

            $setorSelecionado = '';
            if ($documentoId) {
                $buscaDocumento = $this->documentoRepository->find($documentoId);
                $setorSelecionado = $buscaDocumento->setor_id;
            }

            $setores = [];
            $tipoDocumentoSetor = $this->tipoDocumentoSetorRepository->findBy(
                [
                    ['tipo_documento_id', '=', $id]
                ]
            );
            foreach ($tipoDocumentoSetor as $key => $setor) {
                $setores[] = [
                    "id" => $setor->coreSetor->id,
                    "nome" => $setor->coreSetor->nome,
                    "select" => $setor->coreSetor->id == $setorSelecionado ? true : false
                ];
            }

            return response()->json(['response' => 'sucesso', 'data' => $setores]);
        } catch (\Exception $th) {
            dd($th);
            return response()->json(['response' => 'erro']);
        }
    }
}
