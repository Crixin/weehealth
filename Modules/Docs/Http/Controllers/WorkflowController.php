<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Services\{WorkflowService};

class WorkflowController extends Controller
{
    private $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function avancarEtapa(Request $request)
    {
        $data = [
            "documento_id" => $request->documento_id
        ];

        if ($this->workflowService->avancarEtapa($data)['success']) {
            return redirect()->route("docs.documento");
        }
        return redirect()->back();
    }


    public function aprovar(Request $request)
    {
        $data = [
            "documento_id" => $request->documento_id,
            "aprovado" => $request->aprovado,
            "justificativa" => $request->justificativaRejeicao ?? "",
        ];

        if ($this->workflowService->validarEtapaAprovacao($data)['success']) {
            return redirect()->route("docs.documento");
        }
        return redirect()->back();
    }
}
