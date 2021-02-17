<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\{
    WorkflowRepository,
    DocumentoRepository,
    EtapaFluxoRepository,
    UserEtapaDocumentoRepository
};
use Modules\Core\Repositories\{
    ParametroRepository,
};
use Illuminate\Support\Facades\{DB, Storage};
use App\Classes\{Helper, RESTServices};
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
    private $parametroRepository;

    public function __construct()
    {
        $workflow = new Workflow();
        $this->rules = $workflow->rules;

        $this->workflowRepository = new WorkflowRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->parametroRepository = new ParametroRepository();
    }


    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $etapa = $this->etapaFluxoRepository->find($data['etapa_id']);
            $documento = $this->documentoRepository->find($data['documento_id']);
            $etapaAtual = $this->getEtapaAtual($data['documento_id']);

            $buscaWorkFlowAnterior = $this->getWorkflowAnterior($data['documento_id']);
            if (!empty($buscaWorkFlowAnterior) && $buscaWorkFlowAnterior->id != $data['etapa_id']) {
                $updateWorkflowAnterior = [
                    'tempo_duracao_etapa' => $this->getDuracao($buscaWorkFlowAnterior->created_at)
                ];
                $this->update($updateWorkflowAnterior, $buscaWorkFlowAnterior->id);
            }
            if (array_key_exists('iniciar_revisao', $data)) {
                $descricao = $this->replaceText(__('messages.workflow.startReview'));
            } elseif (array_key_exists('iniciar_validacao', $data)) {
                $descricao = $this->replaceText(__('messages.workflow.startValidation'));
            } else {
                $descricao = $data['avancar'] ? $this->replaceText($etapa->descricao) : "A etapa '{$etapa->nome}' foi rejeitada";
            }

            $inserir = [
                'documento_id' => $documento->id,
                'descricao' => $descricao,
                'etapa_fluxo_id' => $etapa->id,
                'justificativa' => $data['justificativa'] ?? null,
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

            $resp = $this->workflowRepository->create($inserir);
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


    public function iniciarRevisao($data)
    {
        try {
            DB::beginTransaction();
            $documento = $data['documento_id'];

            $documento = $this->documentoRepository->find($documento);
            
            $primeiraEtapaFluxo = array_first($documento->docsTipoDocumento->docsFluxo->docsEtapaFluxo->sortBy('ordem')->toArray());
            $info = [
                'documento_id' => $data['documento_id'],
                'etapa_id' => $primeiraEtapaFluxo['id'],
                'iniciar_revisao' => true,

            ];
            if (!$this->store($info)['success']) {
                throw new \Exception('Falha ao salvar o workflow da revisao');
            }
            DB::commit();
            return ['success' => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['success' => false];
        }
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
            DB::beginTransaction();

            $documentoService = new DocumentoService();
            $ged = new RESTServices();


            $documento = $this->documentoRepository->find($data['documento_id']);


            if (!$this->store($data)['success']) {
                throw new \Exception("Falha ao divulgar o documento (workflow) ");
            }
   
            $buscaPrefixo = $this->parametroRepository->getParametro('PREFIXO_TITULO_DOCUMENTO');
            
            $docPath = $documento->nome . $buscaPrefixo . $documento->revisao . "." . $documento->extensao;
            $base64file = base64_encode(Storage::disk('weecode_office')->get($docPath));
            
            $idRegistro = $documento->ged_registro_id;
            
            $areaGed = $this->parametroRepository->getParametro('AREA_GED_DOCUMENTOS');

            if (!$idRegistro) {
                
                $newRegister = [
                    "idArea" => $areaGed,
                    "removido" => false,
                    "listaIndice" => [
                        (object) [
                            'idTipoIndice' => 15,
                            'identificador' => 'documento',
                            'valor' => $documento->codigo
                        ]
                    ]
                    
                ];

                $idRegistro = $ged->postRegistro($newRegister);
                if ($idRegistro['error']) {
                    throw new \Exception("Falha ao criar o registro do documento no GED");
                    
                    return back();
                }
                $idRegistro = $idRegistro['response'];
            }

            $insereDocumento = [
                'endereco' => $documento->revisao . "." . $documento->extensao,
                'idArea' => $areaGed,
                'idRegistro' => $idRegistro,
                'idUsuario' => env('ID_GED_USER'),
                'removido' => false,
                'bytes'    => $base64file
            ];
            
            $response = $ged->postDocumento($insereDocumento);

            if ($response['error']) {
                throw new \Exception("Falha ao criar o documento do registro no GED ");
            }


            $info = [
                "em_revisao" => false,
                "ged_registro_id" => $idRegistro,
            ];

            
            if (!$documentoService->update($info, $data['documento_id'])['success']) {
                throw new \Exception("Falha ao divulgar o documento");
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return ["success" => false];
        }
    }

    public function getDuracao($data)
    {
        return  Helper::format_interval(date_diff(new DateTime(date('Y-m-d H:i:s', strtotime($data)), new DateTimeZone('America/Sao_Paulo')), new DateTime("now", new DateTimeZone('America/Sao_Paulo'))));
    }
}
