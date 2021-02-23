<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use Modules\Docs\Repositories\ListaPresencaRepository;

class ListaPresencaService
{

    protected $listaPresencaRepository;

    public function __construct()
    {
        $this->listaPresencaRepository = new ListaPresencaRepository();
    }

    public function store(array $data)
    {
        try {
            //falta salvar a lista de presenca
            //$this->listaPresencaRepository->create($data);
            $workFlowService = new WorkflowService();
            if (!$workFlowService->avancarEtapa(['documento_id' => $data['documento_id']])['success']) {
                throw new \Exception("Falha ao avançar etapa etapa");
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao avançar a etapa. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }
}
