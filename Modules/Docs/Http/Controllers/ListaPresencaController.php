<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\ListaPresencaRepository;
use Modules\Docs\Services\ListaPresencaService;
use Modules\Docs\Services\WorkflowService;

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
        try {
            $id = $request->idDocumento;
            $buscaDocumento = $this->documentoRepository->find($id);
            $file = $request->file('doc_uploaded', 'local');
            $extensao = $file->getClientOriginalExtension();
            $nome = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $base64 = base64_encode(file_get_contents($file->getRealPath()));

            $cadastro = [
                "lista_presenca_documento" => $base64,
                "extensao"                 => $extensao,
                "nome"                     => $nome,
                "documento_id"             => $id,
                "ged_documento_id"         => '',
                "data"                     => date('d/m/Y'),
                "descricao"                => "Lista de Presença anexada",
                "destinatarios_email"      => '',
                "revisao_documento"        => $buscaDocumento->revisao
            ];


            $listaPresencaService = new ListaPresencaService();
            if (!$listaPresencaService->store($cadastro)['success']) {
                throw new \Exception("Falha ao salvar lista de presenca.");
            }

            $workFlowService = new WorkflowService();
            if (!$workFlowService->avancarEtapa(['documento_id' => $id])['success']) {
                throw new \Exception("Falha ao avançar etapa etapa");
            }

            Helper::setNotify('Nova(s) lista(s) de presença criada(s) com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao gravar a lista de presença', 'danger|close-circle');
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
        $id = $request->idDocumento;
        $buscaDocumento = $this->documentoRepository->find($id);
        $file = $request->file('doc_uploaded', 'local');
        $nome = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extensao = $file->getClientOriginalExtension();
        $base64 = base64_encode(file_get_contents($file->getRealPath()));

        return [
            "documento_id" => $id,
            "nome" => $nome,
            "extensao" => $extensao,
            "data" => date('d/m/Y'),
            "descricao" => "Lista de Presença anexada",
            "destinatarios_email" => '',
            "revisao_documento" => $buscaDocumento->revisao,
            "base64" => $base64
        ];
    }
}
