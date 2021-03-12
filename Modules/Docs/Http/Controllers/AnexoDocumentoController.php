<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\{AnexoRepository, DocumentoRepository};
use Illuminate\Support\Facades\DB;
use App\Classes\Helper;
use Modules\Docs\Services\AnexoService;

class AnexoDocumentoController extends Controller
{

    protected $anexoRepository;
    protected $documentoRepository;
    protected $anexoService;

    public function __construct()
    {
        $this->anexoRepository = new AnexoRepository();
        $this->documentoRepository = new DocumentoRepository();
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
            $buscaDocumento = $this->documentoRepository->find($id);

            $anexoService = new AnexoService();
            $files = $request->file('anexo_escolhido', 'local');
            foreach ($files as $key => $file) {
                $extensao = $file->getClientOriginalExtension();
                $nome = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $base64 = base64_encode(file_get_contents($file->getRealPath()));
                $idRegistro = '';
                if ($buscaDocumento->ged_registro_id) {
                    $data = [
                        'idDocumento' => $id,
                        'base64' => $base64,
                        'nome' => $nome,
                        'extensao' => $extensao
                    ];
                    $response = $anexoService->createAnexoGED($data);
                    if (!$response['success']) {
                        throw new \Exception("Falha ao salvar documento no GED.");
                    }
                    $idRegistro = $response['data'];
                } else {
                    $cadastro['anexo_documento'] = $base64;
                }

                $cadastro["nome"] = $nome;
                $cadastro["documento_id"] = $id;
                $cadastro["ged_documento_id"] = $idRegistro;
                $cadastro['extensao'] = $extensao;

                DB::transaction(function () use ($cadastro) {
                    $anexoService = new AnexoService();
                    $anexoService->create($cadastro);
                });
            }
            Helper::setNotify('Novo(s) anexo(s) criado com sucesso!', 'success|check-circle');
            return true;
        } catch (\Throwable $th) {
            dd($th);
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
        $id = $request->id;
        try {
            DB::transaction(function () use ($id) {
                $anexoService = new AnexoService();
                $buscaAnexo = $this->anexoRepository->find($id);
 
                $anexoService->delete($id);
                if (!$anexoService->deleteAnexoGED($buscaAnexo->ged_documento_id)['success']) {
                    throw new \Exception("Falha ao deletar anexo do documento no GED.");
                }
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            dd($th);
            return response()->json(['response' => 'erro']);
        }
    }

    public function getAnexoGed(Request $request)
    {
        try {
            $id = $request->id;
            $buscaAnexo = $this->anexoRepository->find($id);
            $anexoService = new AnexoService();
            $data = ["id" => $id];

            if (!$anexoService->criaCopiaAnexos($data)['success']) {
                throw new \Exception("Falha ao buscar o anexo do documento no GED.");
            }
            
            return response()->json(['response' => 'sucesso', 'data' => ['caminho' => asset('plugins/onlyoffice-php/doceditor.php?fileID=') . $buscaAnexo->nome . '.' . $buscaAnexo->extensao . '&type=embedded&folder=anexos']]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }
}
