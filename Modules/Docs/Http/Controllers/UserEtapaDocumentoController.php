<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;

class UserEtapaDocumentoController extends Controller
{
    protected $userEtapaDocumentoRepository;

    public function __construct(UserEtapaDocumentoRepository $userEtapaDocumentoRepository)
    {
        $this->userEtapaDocumentoRepository = $userEtapaDocumentoRepository;
    }

    public function aprovadores(Request $request)
    {
        try {
            $etapa = (int) $request->etapa;
            $documento = (int) $request->documento;

            $aprovadores = $this->userEtapaDocumentoRepository->findBy(
                [
                    ['etapa_fluxo_id', '=', $etapa],
                    ['documento_id', '=', $documento, 'AND']
                ]
            );

            $aprov = array_column(json_decode(json_encode($aprovadores), true), 'user_id');
            return response()->json(['response' => 'sucesso', 'data' => $aprov]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }

    }
}
