<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Services\ObservacaoDocumentoService;
use Modules\Docs\Repositories\{ObservacaoDocumentoRepository};

class ObservacaoDocumentoController extends Controller
{

    protected $observacaoDocumentoRepository;

    public function __construct()
    {
        $this->observacaoDocumentoRepository = new ObservacaoDocumentoRepository();
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $observacaoDocumentoService = new ObservacaoDocumentoService();
        $response = $observacaoDocumentoService->store($request->all());

        if ($response['success']) {
            return ["success" => true];
        }
        return ["success" => false];
    }
    
    
    public function buscar(int $documento)
    {
        try {

            $observacoes = $this->observacaoDocumentoRepository->findBy(
                [
                    ['documento_id', "=", $documento]
                ],
                ['coreUsers']
            );
            
            return ["success" => true, "data" => $observacoes];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }
}
