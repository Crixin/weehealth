<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\{
    AgrupamentoUserDocumentoRepository,
    WorkflowRepository,
    DocumentoRepository,
    EtapaFluxoRepository,
    ListaPresencaRepository,
    UserEtapaDocumentoRepository
};
use Modules\Core\Repositories\{
    ParametroRepository,
    UserRepository,
};
use Illuminate\Support\Facades\{DB, Storage};
use App\Classes\{Helper, RESTServices};
use App\Mail\TagDocumentos;
use Illuminate\Support\Facades\Auth;
use Modules\Docs\Model\Workflow;
use App\Services\ValidacaoService;
use DateTime;
use DateTimeZone;
use Modules\Core\Services\NotificacaoService;
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
    private $agrupamentoUserDocumentoRepository;
    private $userRepository;
    private $listaPresencaRepository;

    public function __construct()
    {
        $workflow = new Workflow();
        $this->rules = $workflow->rules;

        $this->workflowRepository = new WorkflowRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->parametroRepository = new ParametroRepository();
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
        $this->userRepository = new UserRepository();
        $this->listaPresencaRepository = new ListaPresencaRepository();
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
                $descricao = Helper::replaceText(__('messages.workflow.startReview'));
            } elseif (array_key_exists('iniciar_validacao', $data)) {
                $descricao = Helper::replaceText(__('messages.workflow.startValidation'));
            } else {
                $descricao = $data['avancar'] ? Helper::replaceText($etapa->descricao) : "A etapa '{$etapa->nome}' foi rejeitada";
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
            dd($th);
            DB::rollback();
            Helper::setNotify(__("messages.workflow.storeFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }

    public function update(array $data, int $id)
    {
        return $this->workflowRepository->update($data, $id);
    }

    public function delete(array $delete)
    {
        try {
            DB::beginTransaction();

            $this->workflowRepository->delete($delete, "id");

            DB::commit();
            Helper::setNotify(__("messages.workflow.deleteSuccess"), 'success|check-circle');
            return ['success' => true];
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            Helper::setNotify(__("messages.workflow.deleteFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }


    public function iniciarRevisao($data)
    {
        try {
            DB::beginTransaction();
            $documento = $data['documento_id'];

            $documento = $this->documentoRepository->find($documento);

            $primeiraEtapaFluxo = array_first($documento->docsTipoDocumento->docsFluxo->docsEtapaFluxo->sortBy('versao_fluxo')->sortBy('ordem')->toArray());
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
                //coloquei isso para testar não sei c eh assim
                if (!$this->store($info)) {
                    throw new \Exception('Falha ao salvar ao enviar para próxima etapa');
                }
            }

            if ($proxEtapa->comportamento_aprovacao) {
                if (!$this->store($info)) {
                    throw new \Exception('Falha ao salvar a aprovação da etapa');
                }
            }

            //Notificação
            if ($proxEtapa->enviar_notificacao && !empty($proxEtapa->notificacao_id)) {
                if (!$this->enviarNotificacaoProxEtapa($proxEtapa, $data)) {
                    throw new \Exception('Falha ao enviar notificação');
                }
            }

            Helper::setNotify(__("messages.workflow.advanceStepSuccess"), 'success|check-circle');
            return ['success' => true];
        } catch (\Throwable $th) {
            dd($th);
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
                        ["etapa_fluxo_id", "=", $etapaAtual->id, "AND"],
                        ["documento_id", "=", $documento->id, "AND"],
                        ["documento_revisao", "=", $documento->revisao, "AND"],
                    ]
                );
                $etapaUser = array_first($userEtapaDocumento->filter(function ($value, $key) {
                    return $value->user_id == Auth::id();
                }));

                $this->userEtapaDocumentoRepository->update(["aprovado" => true], $etapaUser->id);

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
                 //Envio de notificacao de documento aprovado
                $this->validarNotificacaoAprovRejeicao($etapaAtual, $data);
                return ["success" => true];
            }

            if (!$this->retrocederEtapa($data)['success']) {
                throw new \Exception("Falha ao retroceder etapa");
            }
            //Envio de notificacao de documento reprovado
            $this->validarNotificacaoAprovRejeicao($etapaAtual, $data);
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
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
                'bytes'    => $base64file,
                'listaIndice' => [
                    (object) [
                        'idTipoIndice' => 12,
                        'identificador' => 'tipo',
                        'valor' => 'Documento'
                    ]
                ]

            ];
            $response = $ged->postDocumento($insereDocumento);

            if ($response['error']) {
                throw new \Exception("Falha ao criar o documento do registro no GED ");
            }

            $info = [
                "em_revisao" => false,
                "ged_registro_id" => $idRegistro,
                "validade" => date('Y-m-d', strtotime('+' . $documento->docsTipoDocumento->periodo_vigencia . ' month'))
            ];

            if (!$documentoService->update($info, $data['documento_id'])['success']) {
                throw new \Exception("Falha ao divulgar o documento");
            }

            //Processa Anexo não salvos no GED
            if ($documento->revisao == '00') {
                $anexoService = new AnexoService();
                if (!$anexoService->processaAnexo($documento->id)['success']) {
                    throw new \Exception("Falha ao processar anexos de documento");
                }
            }

            //Processa Lista de presenca
            $buscaListaPresenca = $this->listaPresencaRepository->findBy(
                [
                    ["documento_id", "=", $documento->id],
                    ["revisao_documento", "=", $documento->revisao, "AND"],
                    ["ged_documento_id", "=", '', "AND"]
                ]
            );
            if ($buscaListaPresenca) {
                $listaPresencaService = new ListaPresencaService();
                if (!$listaPresencaService->processaListaPresenca($documento->id, $documento->revisao)['success']) {
                    throw new \Exception("Falha ao processar lista de presençao do documento");
                }
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

    public function enviarNotificacaoProxEtapa($proxEtapa, $data)
    {
        try {
            $notificacaoService = new NotificacaoService();
            $objEmailCorpo = $notificacaoService->getCorpoNotificacao($data['documento_id'], $proxEtapa);
            $buscaCorpo = new TagDocumentos($proxEtapa, $data['documento_id']);
            $tagDocumento = $buscaCorpo->substituirTags();

            if ($proxEtapa->comportamento_aprovacao) {
                $usuarios = $this->getUserAprovadores($data['documento_id'], $proxEtapa->id);
                $responseNotificacao = $notificacaoService->sendNotification($proxEtapa->notificacao_id, $usuarios, $objEmailCorpo, $tagDocumento['titulo'], $tagDocumento['corpo']);
            }

            if ($proxEtapa->comportamento_treinamento) {
                $usuarios = $this->getUserTreinamentoDivulgacao('TREINAMENTO', $data['documento_id']);
                $responseNotificacao = $notificacaoService->sendNotification($proxEtapa->notificacao_id, $usuarios, $objEmailCorpo, $tagDocumento['titulo'], $tagDocumento['corpo']);
            }

            if ($proxEtapa->comportamento_divulgacao) {
                $usuarios = $this->getUserTreinamentoDivulgacao('DIVULGACAO', $data['documento_id']);
                $responseNotificacao = $notificacaoService->sendNotification($proxEtapa->notificacao_id, $usuarios, $objEmailCorpo, $tagDocumento['titulo'], $tagDocumento['corpo']);
            }

            //Envio de notificacao msg no sistema OBS: SEMPRE VAI ENVIAR
            $responseNotificacao = $notificacaoService->createNotificacaoSistema($usuarios, $tagDocumento['titulo'], $tagDocumento['corpo'], $tagDocumento['link']);

            if (!$responseNotificacao) {
                throw new \Exception("Erro ao enviar notificação", 1);
            }
            Helper::setNotify(__("messages.workflow.notificationSuccess"), 'success|check-circle');
            return ['success' => true];
        } catch (\Throwable $th) {
            Helper::setNotify(__("messages.workflow.notificationFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }

    public function getUserTreinamentoDivulgacao(string $tipo, int $idDocumento)
    {
        $buscaUsuarios = $this->agrupamentoUserDocumentoRepository->findBy(
            [
                ['documento_id', '=', $idDocumento],
                ['tipo', '=', $tipo]
            ]
        );
        $usuarios = array_column($buscaUsuarios->toArray(), 'user_id');
        $email = $this->userRepository->findBy(
            [
                ['id', '', $usuarios, 'IN']
            ]
        );
        return array_column($email->toArray(), 'email');
    }

    public function getUserAprovadores(int $idDocumento, int $etapa)
    {
        $buscaDocumento = $this->documentoRepository->find($idDocumento);
        $buscaUsuarios = $this->userEtapaDocumentoRepository->findBy(
            [
                ['documento_id', '=', $idDocumento],
                ['documento_revisao', '=', $buscaDocumento->revisao],
                ['etapa_fluxo_id', '=', $etapa]
            ]
        );
        $usuarios = array_column($buscaUsuarios->toArray(), 'user_id');
        $email = $this->userRepository->findBy(
            [
                ['id', '', $usuarios, 'IN']
            ]
        );
        return array_column($email->toArray(), 'email');
    }

    public function validarNotificacaoAprovRejeicao($etapaAtual, $data)
    {
        if ($etapaAtual->enviar_notificacao && !empty($etapaAtual->notificacao_id)) {
            if (!$this->enviarNotificacaoAprovRejeicao($data, $etapaAtual)['success']) {
                throw new \Exception("Falha ao enviar notificação de reprovação da etapa");
            }
        }
    }

    public function enviarNotificacaoAprovRejeicao($data, $etapaAtual)
    {
        try {
            $aprovado = $data['aprovado'];
            $notificacaoService = new NotificacaoService();
            $objEmailCorpo = '';
            $notificacaoId = $aprovado == "true" ? $this->parametroRepository->getParametro('NOTIFICACAO_APROVACAO_DOCUMENTO') : $this->parametroRepository->getParametro('NOTIFICACAO_REJEICAO_DOCUMENTO');
            $objEmailCorpo = $notificacaoService->getCorpoNotificacao($data['documento_id'], $etapaAtual, $notificacaoId);

            $buscaCorpo = new TagDocumentos($etapaAtual, $data['documento_id'], $notificacaoId);
            $tagDocumento = $buscaCorpo->substituirTags();
            $usuarios = $this->getUserAprovadores($data['documento_id'], $etapaAtual->id);

            $notificacaoService->sendNotification($notificacaoId, $usuarios, $objEmailCorpo, $tagDocumento['titulo'], $tagDocumento['corpo']);

            $notificacaoService->createNotificacaoSistema($usuarios, $tagDocumento['titulo'], $tagDocumento['corpo'], $tagDocumento['link']);
            return ['success' => true];
        } catch (\Throwable $th) {
            Helper::setNotify(__("messages.workflow.notificationFail"), 'danger|close-circle');
            return ['success' => false];
        }
    }
}
