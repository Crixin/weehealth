<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\{WorkflowRepository, DocumentoRepository, EtapaFluxoRepository};
use Illuminate\Support\Facades\DB;
use App\Classes\Helper;
use Illuminate\Support\Facades\Auth;
use Modules\Docs\Model\Workflow;
use App\Services\ValidacaoService;

class WorkflowService
{
    private $workflowRepository;
    private $documentoRepository;
    private $etapaFluxoRepository;
    private $rules;

    public function __construct(
        WorkflowRepository $workflowRepository,
        DocumentoRepository $documentoRepository,
        EtapaFluxoRepository $etapaFluxoRepository,
        Workflow $workflow
    ) {
        $this->rules = $workflow->rules;
        $this->workflowRepository = $workflowRepository;
        $this->documentoRepository = $documentoRepository;
        $this->etapaFluxoRepository = $etapaFluxoRepository;
    }

    
    public function storeFirstStep(array $dados)
    {
        try {
            DB::beginTransaction();

            $etapa = $this->etapaFluxoRepository->find($dados['etapa_id']);
            $documento = $this->documentoRepository->find($dados['documento_id']);
            
            $descricao = $this->replaceText($etapa->descricao);


            $inserir = [
                'documento_id' => $documento->id,
                'descricao' => $descricao,
                'etapa_fluxo_id' => $dados['etapa_id'],
                'user_id' => Auth::id(),
                'documento_revisao' => $documento->revisao,
            ];
            $validacao = new ValidacaoService($this->rules, $inserir);
            $errors = $validacao->make();

            if ($errors) {
                DB::rollBack();
                Helper::setNotify(__("messages.workflow.validationFail"), 'danger|close-circle');
                return ['success' => false, 'redirect' => redirect()->back()->withErrors($errors)->withInput()];
            }
            
            $this->workflowRepository->create($inserir);

            DB::commit();
            Helper::setNotify(__("messages.workflow.storeSuccess"), 'success|check-circle');
            return ['success' => true];
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify(__("messages.workflow.storeFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }

    
    public function update(array $data, int $id)
    {
        return $this->workflowRepository->update($data, $id);
    }


    public function delete($delete)
    {
        return $this->workflowRepository->delete($delete);
    }


    private function replaceText(string $text)
    {
        $arrayTexts = [
            "<NOME_USUARIO>" => Auth::user()->name
        ];
        
        foreach ($arrayTexts as $textToFind => $replace) {
            $text = str_replace($textToFind, $replace, $text);
        }

        return $text;
    }


    public function getEtapaAtual(int $documento)
    {
        $documento = $this->documentoRepository->find($documento);

        $historico = $this->workflowRepository->findBy(
            [
                ['documento_id', '=', $documento->id],
                ['documento_revisao', '=', $documento->revisao]
            ],
            [],
            [
                ['created_at', 'ASC']
            ]
        )->toArray();

        return end($historico);
    }
}
