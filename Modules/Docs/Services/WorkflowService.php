<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\{
    WorkflowRepository,
    DocumentoRepository,
    EtapaFluxoRepository,
    UserEtapaDocumentoRepository
};
use Illuminate\Support\Facades\DB;
use App\Classes\Helper;
use Illuminate\Support\Facades\Auth;
use Modules\Docs\Model\Workflow;
use App\Services\ValidacaoService;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Modules\Docs\Services\{
    DocumentoService
};

class WorkflowService
{
    private $rules;
    private $workflowRepository;
    private $documentoRepository;
    private $etapaFluxoRepository;
    private $userEtapaDocumentoRepository;

    public function __construct()
    {
        $workflow = new Workflow();
        $this->rules = $workflow->rules;

        $this->workflowRepository = new WorkflowRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
    }


    public function store(array $dados)
    {
        try {
            DB::beginTransaction();

            $etapa = $this->etapaFluxoRepository->find($dados['etapa_id']);
            $documento = $this->documentoRepository->find($dados['documento_id']);
            $etapaAtual = $this->getEtapaAtual($dados['documento_id']);

            $buscaWorkFlowAnterior = $this->getWorkflowAnterior($dados['documento_id']);
            if (!empty($buscaWorkFlowAnterior) && $buscaWorkFlowAnterior->id != $dados['etapa_id']) {
                $duracao_etapa = $this->getDuracao($buscaWorkFlowAnterior->created_at);
                $updateWorkflowAnterior = [
                    'tempo_duracao_etapa' => $duracao_etapa
                ];
                $this->update($updateWorkflowAnterior, $buscaWorkFlowAnterior->id);
            }

            $descricao = $dados['avancar'] ? $this->replaceText($etapa->descricao) : "A etapa '{$etapa->nome}' foi rejeitada";

            $inserir = [
                'documento_id' => $documento->id,
                'descricao' => $descricao,
                'etapa_fluxo_id' => $etapa->id,
                'justificativa' => $dados['justificativa'] ?? null,
                'user_id' => Auth::id(),
                'documento_revisao' => $documento->revisao
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
            dd($th);
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

        $ultimo = end($historico);
        return $this->etapaFluxoRepository->find(!empty($ultimo) ? $ultimo['etapa_fluxo_id'] : $documento->docsTipoDocumento->docsFluxo->docsEtapaFluxo[0]->id);
    }

    public function getWorkflowAnterior(int $documento)
    {
        $etapaAtual = $this->getEtapaAtual($documento);
        $buscaDocumento = $this->documentoRepository->find($documento);
        return $this->workflowRepository->findOneBy(
            [
                ['documento_id', '=', $documento],
                ['documento_revisao', '=', $buscaDocumento->revisao, "AND"],
                ['etapa_fluxo_id', '=', $etapaAtual->id, "AND"]
            ]
        );
    }

    public function getProximaEtapa(int $documento)
    {
        $etapaAtual = $this->getEtapaAtual($documento);
        
        return $this->etapaFluxoRepository->findOneBy(
            [
                ['fluxo_id', '=', $etapaAtual->fluxo_id],
                ['versao_fluxo', '=', $etapaAtual->versao_fluxo],
                ['ordem', '=', $etapaAtual->ordem + 1],
            ]
        );
    }

    
    public function avancarEtapa(array $data)
    {
        try {
            $etapaAtual = $this->getEtapaAtual($data['documento_id']);
            $proxEtapa = $this->getProximaEtapa($data['documento_id']);
            
            $info = [
                'documento_id' => $data['documento_id'],
                'etapa_id' => $proxEtapa->id,
                'avancar' => true
            ];

            if ($proxEtapa->comportamento_divulgacao) {
                if (!$this->divulgaDocumento($info)) {
                    throw new \Exception('Falha ao divulgar o doc');
                }
            }
            
            if ($proxEtapa->comportamento_treinamento) {
                //
            }
            
            if ($proxEtapa->comportamento_aprovacao) {
                if (!$this->store($info)) {
                    throw new \Exception('Falha ao salvar a aprovação da etapa');
                }
            }
            Helper::setNotify(__("messages.workflow.advanceStepSuccess"), 'success|check-circle');
            return ['success' => true];
        } catch (\Throwable $th) {
            Helper::setNotify(__("messages.workflow.advanceStepFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }

    
    private function retrocederEtapa(array $data)
    {
        try {
            $etapa = $this->getEtapaAtual($data['documento_id']);
            
            $info = [
                'documento_id' => $data['documento_id'],
                'justificativa' => $data['justificativa'],
                'etapa_id' => $etapa->etapa_rejeicao_id,
                'avancar' => false
            ];
            if (!$this->store($info)['success']) {
                throw new \Exception('Falha ao salvar o retrocesso da etapa');
            }

            Helper::setNotify(__("messages.workflow.retreatStepSuccess"), 'success|check-circle');
            return ['success' => true];
        } catch (\Throwable $th) {
            Helper::setNotify(__("messages.workflow.retreatStepFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }
    


    public function validarEtapaAprovacao(array $data)
    {
        try {
            $etapaAtual = $this->getEtapaAtual($data['documento_id']);
            $documento = $this->documentoRepository->find($data["documento_id"]);
        
            if (filter_var($data["aprovado"], FILTER_VALIDATE_BOOLEAN)) {
                DB::beginTransaction();

                $userEtapaDocumento = $this->userEtapaDocumentoRepository->findBy(
                    [
                        ["etapa_fluxo_id", "=", $etapaAtual->id],
                        ["documento_id", "=", $documento->id],
                        ["documento_revisao", "=", $documento->revisao],
                    ]
                );
                
                $etapaUser = array_first($userEtapaDocumento->filter(function ($value, $key) {
                    return $value->user_id == Auth::id();
                }));
                $this->userEtapaDocumentoRepository->update([
                    "aprovado" => true
                ], $etapaUser->id);

                $avancar = false;

                if ($etapaAtual->tipo_aprovacao_id == 1) {
                    //TIPO 1 = APROVAÇÃO SIMPLES (UM APROVADOR AVANÇA A ETAPA)
                    $avancar = true;
                } elseif ($etapaAtual->tipo_aprovacao_id == 2) {
                    //TIPO 2 = APROVAÇÃO CONDICIONADA (TODOS APROVADOR APROVAM PARA AVANÇAR A ETAPA)
                    $qtdEtapasAprovadas = count($userEtapaDocumento->filter(function ($value, $key) {
                        return $value->aprovado;
                    }));

                    //QTD ATUAL MAIS A DE AGORA
                    if ($qtdEtapasAprovadas + 1 == count($etapaUser)) {
                        $avancar = true;
                    }
                }

                if ($avancar) {
                    if (!$this->avancarEtapa(['documento_id' => $data['documento_id']])['success']) {
                        throw new \Exception("Falha ao retroceder etapa");
                    }
                }

                DB::commit();
                return ["success" => true];
            }

            if (!$this->retrocederEtapa($data)['success']) {
                throw new \Exception("Falha ao retroceder etapa");
            }
            
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return ["success" => false];
        }
    }


    private function divulgaDocumento(array $data)
    {
        try {
            $documentoService = new DocumentoService();

            if (!$this->store($data)['success']) {
                throw new \Exception("Falha ao divulgar o documento (workflow) ");
            }
            
            $info = [
                "em_revisao" => false,
            ];
            
            if (!$documentoService->update($info, $data['documento_id'])['success']) {
                throw new \Exception("Falha ao divulgar o documento");
            }

            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }

    public function getDuracao($data)
    {
        return  Helper::format_interval(date_diff(new DateTime(date('Y-m-d H:i:s', strtotime($data)), new DateTimeZone('America/Sao_Paulo')), new DateTime("now", new DateTimeZone('America/Sao_Paulo'))));
    }
}
