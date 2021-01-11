<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\AnexoRepository;
use Modules\Docs\Repositories\DocumentoRepository;
use Illuminate\Support\Facades\DB;
use App\Classes\Helper;
use Modules\Docs\Services\AnexoService;

class AnexoDocumentoController extends Controller
{

    protected $anexoRepository;
    protected $documentoRepository;
    protected $anexoService;

    public function __construct(
        AnexoRepository $anexoRepository,
        DocumentoRepository $documentoRepository,
        AnexoService $anexoService
    )
    {
        $this->anexoRepository = $anexoRepository;
        $this->documentoRepository = $documentoRepository;
        $this->anexoService = $anexoService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $anexos = $this->anexoRepository->findBy(
                [
                    ['documento_id', '=', $request->id]
                ],
                [],
                [
                    ['created_at', 'ASC']
                ]
            );

            return response()->json(['response' => 'sucesso', 'data' => $anexos]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
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
        try {
            $id = $request->idDocumento;
            $files = $request->file('anexo_escolhido', 'local');
            foreach ($files as $key => $file) {
                $extensao = $file->getClientOriginalExtension();
                $nome = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $cadastro = [
                    "nome" => $nome,
                    "documento_id" => $id,
                    "ged_documento_id" => ''
                ];

                DB::transaction(function () use ($cadastro) {
                    $this->anexoService->create($cadastro);
                });
            }
            Helper::setNotify('Novo(s) anexo(s) criado com sucesso!', 'success|check-circle');
            return true;
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o anexo', 'danger|close-circle');
            return false;
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
                $this->anexoService->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

}
