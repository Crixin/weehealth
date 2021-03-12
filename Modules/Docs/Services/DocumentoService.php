<?php

namespace Modules\Docs\Services;

use App\Classes\{Helper, RESTServices};
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\{DB, Storage, Auth};
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\SetorRepository;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Model\Documento;
use Modules\Docs\Repositories\{
    DocumentoRepository,
    TipoDocumentoRepository,
    AgrupamentoUserDocumentoRepository,
    DocumentoItemNormaRepository,
    HierarquiaDocumentoRepository,
    ItemNormaRepository,
    TipoDocumentoSetorRepository,
    UserEtapaDocumentoRepository,
    VinculoDocumentoRepository,
    WorkflowRepository
};
use Modules\Docs\Services\{WorkflowService, UserEtapaDocumentoService, HistoricoDocumentoService};

class DocumentoService
{
    protected $documentoRepository;
    protected $userRepository;
    protected $itemNormaRepository;
    protected $userEtapaDocumentoRepository;
    protected $hierarquiaDocumentoRepository;
    protected $vinculoDocumentoRepository;
    protected $tipoDocumentoRepository;
    protected $agrupamentoUserDocumentoRepository;
    protected $documentoItemNormaRepository;
    protected $parametroRepository;
    protected $workflowRepository;

    protected $rules;

    public function __construct()
    {
        $documento = new Documento();
        $this->rules = $documento->rules;

        $this->userRepository = new UserRepository();
        $this->workflowRepository = new WorkflowRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->itemNormaRepository = new ItemNormaRepository();
        $this->tipoDocumentoRepository = new TipoDocumentoRepository();
        $this->vinculoDocumentoRepository = new VinculoDocumentoRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->documentoItemNormaRepository = new DocumentoItemNormaRepository();
        $this->hierarquiaDocumentoRepository = new HierarquiaDocumentoRepository();
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
        $this->parametroRepository = new ParametroRepository();
    }

    public function store($data)
    {
        try {
            $createDocumento = $data;
            unset(
                $createDocumento['hierarquia_documento'],
                $createDocumento['vinculo_documento'],
                $createDocumento['grupo_treinamento'],
                $createDocumento['grupo_divulgacao'],
                $createDocumento['item_normas'],
                $createDocumento['etapa_aprovacao']
            );

            $documento = DB::transaction(function () use ($createDocumento, $data) {
                $workflowService = new WorkflowService();
                $tipoDocumentoSetorService = new TipoDocumentoSetorService();


                $documento = $this->documentoRepository->create($createDocumento);

                $versãoFluxo = $documento->docsTipoDocumento->docsFluxo->versao;

                $primeiraEtapaFluxo = array_first(array_filter($documento->docsTipoDocumento->docsFluxo->docsEtapaFluxo->toArray(), function ($etapa) use ($versãoFluxo) {
                    return $etapa['ordem'] == 1 && $etapa['versao_fluxo'] == $versãoFluxo;
                }));

                $dataWorkflow = [
                    'etapa_id' => $primeiraEtapaFluxo['id'],
                    'documento_id' => $documento->id,
                    'avancar' => true
                ];

                if (!$workflowService->store($dataWorkflow)['success']) {
                    throw new \Exception('Falha ao criar workflow');
                }

                /**Cria Hierarquia Documento */
                if (array_key_exists("hierarquia_documento", $data)) {
                    if (!$this->hierarquiaDocumentos($documento->id, $data['hierarquia_documento'])['success']) {
                        throw new \Exception("Falha na hierarquia de documentos");
                    }
                }

                /**Cria Vinculo de Documento */
                if (array_key_exists("vinculo_documento", $data)) {
                    if (!$this->vinculoDocumentos($documento->id, $data['vinculo_documento'])['success']) {
                        throw new \Exception("Falha no vinculo de documentos");
                    }
                }

                /**Cria Agrupamento de Documento (Treinamento) */
                if (array_key_exists("grupo_treinamento", $data)) {
                    if (!$this->agrupamentosUserDocumento($documento->id, $documento->revisao, $data['grupo_treinamento'], "TREINAMENTO")['success']) {
                        throw new \Exception("Falha no grupo de treinamento");
                    }
                }


                /**Cria Agrupamento de Documento (Divulgacao) */
                if (array_key_exists("grupo_divulgacao", $data)) {
                    if (!$this->agrupamentosUserDocumento($documento->id, $documento->revisao, $data['grupo_divulgacao'], "DIVULGACAO")['success']) {
                        throw new \Exception("Falha no grupo de divulgação");
                    }
                }


                /**Cria Itens Norma */
                if (array_key_exists("item_normas", $data)) {
                    $itensNorma =  array_column($data['item_normas'], 'item_norma_id');
                    if (!$this->itensNorma($documento->id, $itensNorma)['success']) {
                        throw new \Exception("Falha nos itens da norma");
                    }
                }

                /**Etapa de Aprovação */
                if (array_key_exists("etapa_aprovacao", $data)) {
                    if (!$this->aprovadores($documento->id, $data['etapa_aprovacao'])['success']) {
                        throw new \Exception("Falha no cad/alt de aprovadores");
                    }
                }

                $tipoDocumentoSetorService->atualizaUltimoCodigoTipoDocumento($data['tipo_documento_id'], $data['setor_id']);


                return $documento;
            });
            return response()->json(["success" => true, "data" => ['documento_id' => $documento->id]]);
        } catch (\Throwable $th) {
            return response()->json(["success" => false]);
        }
    }

    public function update($data, $id)
    {
        try {
            DB::beginTransaction();


            $this->rules['tituloDocumento'] .= "," . $id;
            $updateDocumento = $data;
            unset(
                $updateDocumento['codigo'],
                $updateDocumento['hierarquia_documento'],
                $updateDocumento['vinculo_documento'],
                $updateDocumento['grupo_treinamento'],
                $updateDocumento['grupo_divulgacao'],
                $updateDocumento['item_normas'],
                $updateDocumento['etapa_aprovacao'],
                $updateDocumento['setor_id']
            );

            $documento = $this->documentoRepository->find($id);

            $this->documentoRepository->update($updateDocumento, $id);

            /**Cria Hierarquia Documento */
            if (array_key_exists("hierarquia_documento", $data)) {
                if (!$this->hierarquiaDocumentos($documento->id, $data['hierarquia_documento'])['success']) {
                    throw new \Exception("Falha na hierarquia de documentos");
                }
            }

            /**Cria Vinculo de Documento */
            if (array_key_exists("vinculo_documento", $data)) {
                if (!$this->vinculoDocumentos($documento->id, $data['vinculo_documento'])['success']) {
                    throw new \Exception("Falha no vinculo de documentos");
                }
            }

            /**Cria Agrupamento de Documento (Treinamento) */
            if (array_key_exists("grupo_treinamento", $data)) {
                if (!$this->agrupamentosUserDocumento($documento->id, $documento->revisao, $data['grupo_treinamento'], "TREINAMENTO")['success']) {
                    throw new \Exception("Falha no grupo de treinamento");
                }
            }

            /**Cria Agrupamento de Documento (Divulgacao) */
            if (array_key_exists("grupo_divulgacao", $data)) {
                if (!$this->agrupamentosUserDocumento($documento->id, $documento->revisao, $data['grupo_divulgacao'], "DIVULGACAO")['success']) {
                    throw new \Exception("Falha no grupo de divulgação");
                }
            }

            /**Cria Itens Norma */
            if (array_key_exists("item_normas", $data)) {
                $itensNorma =  array_column($data['item_normas'], 'item_norma_id');
                if (!$this->itensNorma($documento->id, $itensNorma)['success']) {
                    throw new \Exception("Falha nos itens da norma");
                }
            }

            /**Etapa de Aprovação */
            if (array_key_exists("etapa_aprovacao", $data)) {
                if (!$this->aprovadores($documento->id, $data['etapa_aprovacao'])['success']) {
                    throw new \Exception("Falha no cad/alt de aprovadores");
                }
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }


    public function gerarCodigoDocumento($tipoDocumento, $setor)
    {
        $buscaTipoDocumento = $this->tipoDocumentoRepository->find($tipoDocumento);
        $codigoPadrao = json_decode($buscaTipoDocumento->codigo_padrao);

        $setorRepository = new SetorRepository();
        $buscaSetor = $setorRepository->find($setor);

        $tipoDocumentoSetor = new TipoDocumentoSetorRepository();
        $buscaUltimoNumero = $tipoDocumentoSetor->findOneBy(
            [
                ["tipo_documento_id", "=", $tipoDocumento],
                ["setor_id", "=", $setor, "AND"]
            ]
        );

        $parametroRepository = new ParametroRepository();
        $buscaParametros = (array)json_decode($parametroRepository->getParametro('PADRAO_CODIGO'));

        $codigoFinal = '';
        foreach ($codigoPadrao as $key => $value) {

            switch ($buscaParametros[$value]->VARIAVEL) {
                case 'SIGLA':
                    $codigoFinal .= trim($buscaTipoDocumento->sigla);
                    break;
                case 'NUMEROPADRAO':
                    $codigoFinal .= $this->gerarPadraoNumero(
                        $buscaUltimoNumero->ultimo_documento ? $buscaUltimoNumero->ultimo_documento + 1 : 1,
                        $buscaTipoDocumento->numero_padrao_id
                    );
                    break;

                case 'SETOR':
                    $codigoFinal .= trim($buscaSetor->sigla);
                    break;

                case 'SEPARADOR':
                    $codigoFinal .= trim($buscaParametros[$value]->DESCRICAO);
                    break;
            }
        }
        return $codigoFinal;
    }


    public function gerarPadraoNumero($numero, $padrao)
    {
        $codigo = "0";
        switch ($padrao) {
            case 1:
                $codigo = $numero;
                break;
            case 2:
                $codigo = ( strlen($numero) <= 1 ) ? str_pad($numero, 2, '0', STR_PAD_LEFT) : $numero;
                break;
            case 3:
                if (strlen($numero) <= 1) {
                    $codigo = str_pad($numero, 3, '0', STR_PAD_LEFT);
                } elseif (strlen($numero) == 2) {
                    $codigo = str_pad($numero, 3, '0', STR_PAD_LEFT);
                } else {
                    $codigo = $numero;
                }
                break;
            default:
                $valor = $numero + ".01";

                if (strlen($numero) <= 1) {
                    $codigo = str_pad($valor, 3, '0', STR_PAD_LEFT);
                } elseif (strlen($numero) == 2) {
                    $codigo = str_pad($valor, 2, '0', STR_PAD_LEFT);
                } else {
                    $codigo = $valor;
                }
                break;
        }
        return $codigo;
    }

    
    public function validador($data)
    {
        $tipoDocumentoService = new TipoDocumentoService();
        $buscaTipoDocumento = $this->tipoDocumentoRepository->find($data->tipoDocumento);
        //verifica se o tipo de documento exige vinculo obrigatorio
        if ($buscaTipoDocumento->vinculo_obrigatorio == true) {
            $this->rules["documentoPai"] = "required|array|min:1";
            $this->rules["documentoPai.*"] = "required|string|distinct|min:1";
        }

        //verifica se o tipo de documento exige vinculo a outro tipo de documento
        if ($buscaTipoDocumento->vinculo_obrigatorio_outros_documento == true) {
            $this->rules["documentoVinculado"] = "required|array|min:1";
            $this->rules["documentoVinculado.*"] = "required|string|distinct|min:1";
        }

        //verifica as etapas de aprovação
        $etapas = $tipoDocumentoService->getEtapasFluxosPorComportamento(
            $data->tipoDocumento,
            'comportamento_aprovacao'
        );
        foreach ($etapas['etapas'] as $key => $value) {
            $variavel = 'grupo' . $value['id'];
            if ($data->$variavel) {
                $this->rules[$variavel] = "required|array|min:1";
                $this->rules[$variavel . ".*"] = "required|string|distinct|min:1";
            }
        }

        $validacao = new ValidacaoService($this->rules, $data->all());
        $errors = $validacao->make();

        if ($errors) {
            return $errors;
        }

        return false;
    }


    public function iniciarRevisao($data)
    {
        try {
            DB::beginTransaction();

            $documento = $data['documento_id'];

            $this->criaCopiaDocumento($data);

            $update = [
                'revisao' => $this->getNextCodigoRevisao($documento),
                'em_revisao' => true
            ];

            $this->documentoRepository->update($update, $documento);

            $workflowService = new WorkflowService();
            $userEtapaDocumentoService = new UserEtapaDocumentoService();
            $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();

            if (!$workflowService->iniciarRevisao($data)['success']) {
                throw new \Exception("Falha ao iniciar o workflow de revisao");
            }

            if (!$userEtapaDocumentoService->iniciarRevisao($data)['success']) {
                throw new \Exception("Falha ao criar lista de aprovadores para a nova revisao");
            }
            
            if (!$agrupamentoUserDocumentoService->iniciarRevisao($data)['success']) {
                throw new \Exception("Falha ao criar grupo de treinamento e divulgação para a nova revisao");
            }
            

            DB::commit();
            Helper::setNotify(__("messages.documento.startReview"), 'success|check-circle');
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify(__("messages.documento.startReviewFailed"), 'danger|close-circle');
            return ["success" => false];
        }
    }


    private function getPreviousCodigoRevisao($documento)
    {
        $documento = $this->documentoRepository->find($documento);
        return str_pad($documento->revisao - 1, strlen($documento->revisao), "0", STR_PAD_LEFT);
    }


    private function getNextCodigoRevisao($documento)
    {
        $documento = $this->documentoRepository->find($documento);
        return str_pad($documento->revisao + 1, strlen($documento->revisao), "0", STR_PAD_LEFT);
    }


    private function hierarquiaDocumentos(int $documento, array $docsVincular)
    {
        try {
            $hierarquiaDocumentoService = new HierarquiaDocumentoService();

            $documentosHierarquiaDelete = $this->hierarquiaDocumentoRepository->findBy(
                [
                    ['documento_id', '=', $documento],
                    ['documento_pai_id', "", $docsVincular ?? [] ,"NOTIN"]
    
                ]
            )->pluck("id")->toArray();
    
            $infoCreate = [
                "hierarquia_documento" => $docsVincular,
                "documento_id" => $documento
            ];

            if (!$hierarquiaDocumentoService->store($infoCreate)['success']) {
                throw new \Exception("falha ao inserir hierarquia");
            }
            if (!$hierarquiaDocumentoService->delete($documentosHierarquiaDelete)['success']) {
                throw new \Exception("falha ao remover hierarquia");
            }
    
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }
    
    
    private function vinculoDocumentos(int $documento, array $docsVincular)
    {
        try {
            $vinculoDocumentoService = new VinculoDocumentoService();

            $documentosVinculadosDelete = $this->vinculoDocumentoRepository->findBy(
                [
                    ['documento_id', '=', $documento],
                    ['documento_vinculado_id', "", $docsVincular ?? [] ,"NOTIN"]
    
                ]
            )->pluck("id")->toArray();
    
            $infoCreate = [
                "documento_vinculado_id" => $docsVincular,
                "documento_id" => $documento
            ];

            if (!$vinculoDocumentoService->store($infoCreate)['success']) {
                throw new \Exception("falha ao inserir vinculo de documentos");
            }
            if (!$vinculoDocumentoService->delete($documentosVinculadosDelete)['success']) {
                throw new \Exception("falha ao remover vinculo de documentos");
            }
    
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    private function agrupamentosUserDocumento(int $documento, string $revisao, array $usersGrupoAgrupamento, string $tipo)
    {
        try {
            $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();

            $buscaTodosUsuarios = $this->agrupamentoUserDocumentoRepository->findBy(
                [
                    ["documento_id", "=", $documento],
                    ["tipo", "=", $tipo, "AND"],
                    ["documento_revisao", "=", $revisao, "AND"]
                ]
            );

            $userGrupoAux = [];
            $usersAgrupamentoCadastrados = [];

            foreach ($buscaTodosUsuarios as $key => $value) {
                $usersAgrupamentoCadastrados[$value['id']] = $value['grupo_id'] . '-' . $value['user_id'];
            }

            foreach ($usersGrupoAgrupamento ?? [] as $key => $value) {
                $userGrupoAux[] = $value['grupo_id'] . '-' . $value['user_id'];
            }

            $delete = array_filter($usersAgrupamentoCadastrados, function ($arr) use ($userGrupoAux) {
                if (!in_array($arr, $userGrupoAux)) {
                    return $arr;
                }
            });

            $delete = array_keys($delete);

            $infoCreate = [
                "documento_id" => $documento,
                "grupo_and_user" => $usersGrupoAgrupamento,
                "tipo" => $tipo,
                "documento_revisao" => $revisao
            ];

            if (!$agrupamentoUserDocumentoService->store($infoCreate)['success']) {
                throw new \Exception("falha ao inserir agrupamentos no documento");
            }
            if (!$agrupamentoUserDocumentoService->delete($delete)['success']) {
                throw new \Exception("falha ao remover agrupamentos no documento");
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    private function itensNorma(int $documento, array $itensNormas)
    {
        try {
            $documentoItemNormaService = new DocumentoItemNormaService();

            $buscaTodosItemNormaDelete = $this->documentoItemNormaRepository->findBy(
                [
                    ['documento_id', '=', $documento],
                    ['item_norma_id', "", $itensNormas ?? [] ,"NOTIN"]
                ]
            )->pluck("id")->toArray();

            $infoCreate = [
                "item_norma_id" => $itensNormas,
                "documento_id" => $documento
            ];

            if (!$documentoItemNormaService->store($infoCreate)['success']) {
                throw new \Exception("falha ao inserir normas no documentos");
            }
            if (!$documentoItemNormaService->delete($buscaTodosItemNormaDelete)['success']) {
                throw new \Exception("falha ao remover normas no documentos");
            }

            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    private function aprovadores(int $documento, array $aprovadores)
    {
        try {
            $userEtapaDocumentoService = new UserEtapaDocumentoService();
            $documento = $this->documentoRepository->find($documento);

            $allUserEtapaDocumento = $this->userEtapaDocumentoRepository->findBy(
                [
                    ['documento_id', '=', $documento->id]
                ]
            )->toArray();

            $delete = array_filter($allUserEtapaDocumento, function ($arr) use ($aprovadores) {
                foreach ($aprovadores['grupo_user_etapa'] as $aprovador) {
                    if (
                        $arr['user_id'] == $aprovador['user_id'] &&
                        $arr['etapa_fluxo_id'] == $aprovador['etapa_fluxo_id'] &&
                        $arr['grupo_id'] == $aprovador['grupo_id']
                    ) {
                        return false;
                    }
                }
                return $arr;
            });

            $delete = array_column($delete, "id");

            $aprovadores['documento_id'] = $documento->id;
            $aprovadores['documento_revisao'] = $documento->revisao;

            if (!$userEtapaDocumentoService->store($aprovadores)['success']) {
                throw new \Exception("falha ao inserir aprovadores no documento");
            }
            if (!$userEtapaDocumentoService->delete($delete)['success']) {
                throw new \Exception("falha ao remover aprovadores no documento");
            }

            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    public function criaCopiaDocumento(array $data)
    {
        try {
            $ged = new RESTServices();

            $documentoService = new DocumentoService();
            $documento = $this->documentoRepository->find($data['documento_id']);


            $response = $ged->getRegistro($documento->ged_registro_id, ["docs" => "true"]);

            if ($response['error']) {
                throw new \Exception("Falha na busca do registro para criar uma cópia para a nova revisão");
            }

            $registro = $response['response'];

            $nomeDocumento = $documento->revisao . "." . $documento->extensao;

            foreach ($registro->listaDocumento as $documentoGed) {
                if ($documentoGed->endereco == $nomeDocumento) {
                    $response = $ged->getDocumento($documentoGed->id, ['docs' => 'true']);

                    if ($response['error']) {
                        throw new \Exception("Falha na busca do documento para criar uma cópia para a nova revisão");
                    }

                    $documentoToClone = $response['response'];

                    $buscaPrefixo = $this->parametroRepository->getParametro('PREFIXO_TITULO_DOCUMENTO');

                    $novaRev = str_pad($data['revisao'], strlen($documento->revisao), "0", STR_PAD_LEFT);

                    $nomeDocumentoFinal = $documento->nome . $buscaPrefixo . $novaRev . "." . $documento->extensao;

                    $storagePath = Storage::disk('weecode_office')->put($nomeDocumentoFinal, base64_decode($documentoToClone->bytes));
                    break;
                }
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    public function cancelarRevisao(array $data)
    {
        try {
            DB::beginTransaction();

            $workflowService = new WorkflowService();
            $userEtapaDocumentoService = new UserEtapaDocumentoService();
            $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();
            $historicoDocumentoService = new HistoricoDocumentoService();
            
            $documento = $this->documentoRepository->find($data['documento_id']);
            $update = [
                'revisao' => $this->getPreviousCodigoRevisao($documento->id),
                'em_revisao' => false
            ];
            
            $this->documentoRepository->update($update, $documento->id);
            
            $userEtapaDocumentoDelete = $this->userEtapaDocumentoRepository->findBy(
                [
                    ['documento_id', "=", $documento->id],
                    ['documento_revisao', "=", $documento->revisao],
                ]
            )->pluck('id')->toArray();

            $agrupamentoUserDocumentoDelete = $this->agrupamentoUserDocumentoRepository->findBy(
                [
                    ['documento_id', "=", $documento->id],
                    ['documento_revisao', "=", $documento->revisao],
                ]
            )->pluck('id')->toArray();

            $workflowDelete = $this->workflowRepository->findBy(
                [
                    ['documento_id', "=", $documento->id],
                    ['documento_revisao', "=", $documento->revisao],
                ]
            )->pluck('id')->toArray();

            $registraHistorico = [
                "documento_id" => $documento->id,
                "user_id" => Auth::id(),
                "documento_revisao" => $documento->revisao,
                "descricao" => Helper::replaceText(__("messages.workflow.cancelReview") . ': ' . $data['justificativaCancelamento'])
            ];

            if (!$userEtapaDocumentoService->delete($userEtapaDocumentoDelete, false)['success']) {
                throw new \Exception("Falha ao deletar user etapa documento no cancelamento da revisao");
            }

            if (!$agrupamentoUserDocumentoService->delete($agrupamentoUserDocumentoDelete)['success']) {
                throw new \Exception("Falha ao deletar agrupamento user documento no cancelamento da revisao");
            }

            if (!$workflowService->delete($workflowDelete)['success']) {
                throw new \Exception("Falha ao deletar workflow no cancelamento da revisao");
            }
            
            if (!$historicoDocumentoService->store($registraHistorico)['success']) {
                throw new \Exception("Falha ao criar historico do cancelamento da revisao");
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
