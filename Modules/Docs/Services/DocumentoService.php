<?php

namespace Modules\Docs\Services;

use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\SetorRepository;
use Modules\Docs\Model\Documento;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\TipoDocumentoRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Repositories\HierarquiaDocumentoRepository;
use Modules\Docs\Repositories\ItemNormaRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;

class DocumentoService
{
    protected $documentoRepository;
    protected $userRepository;
    protected $itemNormaRepository;
    protected $userEtapaDocumentoRepository;


    protected $hierarquiaDocumentoService;
    protected $vinculoDocumentoService;
    protected $agrupamentoUserDocumentoService;
    protected $documentoItemNormaService;
    protected $userEtapaDocumentoService;
    protected $tipoDocumentoService;
    protected $workflowService;

    protected $rules;

    public function __construct(
        Documento $documento,
        DocumentoRepository $documentoRepository,
        HierarquiaDocumentoService $hierarquiaDocumentoService,
        VinculoDocumentoService $vinculoDocumentoService,
        AgrupamentoUserDocumentoService $agrupamentoUserDocumentoService,
        UserEtapaDocumentoService $userEtapaDocumentoService,
        DocumentoItemNormaService $documentoItemNormaService,
        TipoDocumentoService $tipoDocumentoService,
        WorkflowService $workFlowService,
        UserRepository $userRepository,
        ItemNormaRepository $itemNormaRepository,
        UserEtapaDocumentoRepository $userEtapaDocumentoRepository
    ) {
        $this->rules = $documento->rules;

        $this->documentoRepository = $documentoRepository;
        $this->userRepository = $userRepository;
        $this->itemNormaRepository = $itemNormaRepository;
        $this->userEtapaDocumentoRepository = $userEtapaDocumentoRepository;

        $this->hierarquiaDocumentoService = $hierarquiaDocumentoService;
        $this->vinculoDocumentoService = $vinculoDocumentoService;
        $this->agrupamentoUserDocumentoService = $agrupamentoUserDocumentoService;
        $this->userEtapaDocumentoService = $userEtapaDocumentoService;
        $this->documentoItemNormaService = $documentoItemNormaService;
        $this->tipoDocumentoService = $tipoDocumentoService;
        $this->workflowService = $workFlowService;
    }

    public function create($data)
    {
        $createDocumento = $data;
        unset(
            $createDocumento['hierarquia_documento'],
            $createDocumento['vinculo_documento'],
            $createDocumento['grupo_treinamento'],
            $createDocumento['grupo_divulgacao'],
            $createDocumento['item_normas'],
            $createDocumento['etapa_aprovacao']
        );
        try {
            DB::transaction(function () use ($createDocumento, $data) {
                $documento = $this->documentoRepository->create($createDocumento);

                $requestWorkflow = $this->montaRequestWorkflow($data['tipo_documento_id'], $documento->id, $data['revisao']);
                $this->workflowService->create($requestWorkflow);

                /**Cria Hierarquia Documento */
                foreach ($data['hierarquia_documento'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $this->hierarquiaDocumentoService->create($value);
                }

                /**Cria Vinculo de Documento */
                foreach ($data['vinculo_documento'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $this->vinculoDocumentoService->create($value);
                }

                /**Cria Agrupamento de Documento (Treinamento) */
                foreach ($data['grupo_treinamento'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $value['tipo'] = 'TREINAMENTO';
                    $this->agrupamentoUserDocumentoService->create($value);
                }

                /**Cria Agrupamento de Documento (Divulgacao) */
                foreach ($data['grupo_divulgacao'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $value['tipo'] = 'DIVULGACAO';
                    $this->agrupamentoUserDocumentoService->create($value);
                }

                /**Cria Documento Item Norma */
                foreach ($data['item_normas'] ?? [] as $value) {
                    $value['documento_id'] = $documento->id;
                    $this->documentoItemNormaService->create($value);
                }

                /**Etapa de Aprovação */
                foreach ($data['etapa_aprovacao'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $this->userEtapaDocumentoService->create($value);
                }
                $this->tipoDocumentoService->atualizaUltimoCodigoTipoDocumento($data['tipo_documento_id']);
            });
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function update($data, $id)
    {
        $updateDocumento = $data;
        unset(
            $updateDocumento['codigo'],
            $updateDocumento['hierarquia_documento'],
            $updateDocumento['vinculo_documento'],
            $updateDocumento['grupo_treinamento'],
            $updateDocumento['grupo_divulgacao'],
            $updateDocumento['item_normas'],
            $updateDocumento['etapa_aprovacao']
        );
        try {

            $this->documentoRepository->update($updateDocumento, $id);

            /**Cria Hierarquia Documento */
            $buscaTodosDocumentos = $this->documentoRepository->findAll();
            $documentos =  array_column(json_decode(json_encode($buscaTodosDocumentos), true), 'id');
            $hierarquia = array();
            foreach ($data['hierarquia_documento'] as $key => $value) {
                array_push($hierarquia, $value['documento_pai_id']);
            }
            $diff_para_create  = array_diff($hierarquia, $documentos);
            $diff_para_detete = array_diff($documentos, $hierarquia);

            foreach ($diff_para_create as $key => $doc) {
                $this->hierarquiaDocumentoService->create(["documento_id" => $id,"documento_pai_id"  => $doc]);
            }

            foreach ($diff_para_detete as $key => $doc) {
                $this->hierarquiaDocumentoService->delete(
                    [
                        ['documento_id','=',$id],
                        ['documento_pai_id','=',$doc,"AND"]
                    ]
                );
            }

            /**Cria Vinculo de Documento */
            $vinculado = array();
            foreach ($data['vinculo_documento'] as $key => $value) {
                array_push($vinculado, $value['documento_vinculado_id']);
            }
            $diff_para_create_vinculo  = array_diff($vinculado, $documentos);
            $diff_para_detete_vinculo = array_diff($documentos, $vinculado);

            foreach ($diff_para_create_vinculo as $key => $doc) {
                $this->vinculoDocumentoService->create(["documento_id" => $id,"documento_vinculado_id"  => $doc]);
            }

            foreach ($diff_para_detete_vinculo as $key => $doc) {
                $this->vinculoDocumentoService->delete(
                    [
                        ['documento_id','=',$id],
                        ['documento_vinculado_id','=',$doc,"AND"]
                    ]
                );
            }

            /**Cria Agrupamento de Documento (Treinamento) */

            $buscaTodosUsuarios = $this->userRepository->findAll();
            $users =  array_column(json_decode(json_encode($buscaTodosUsuarios), true), 'id');
            $grupoTreinamento = array();
            foreach ($data['grupo_treinamento'] as $key => $value) {
                array_push($grupoTreinamento, $value['user_id']);
            }

            $diff_para_create_user  = array_diff($grupoTreinamento, $users);
            $diff_para_detete_user = array_diff($users, $grupoTreinamento);

            foreach ($diff_para_create_user as $key => $user) {
                $this->agrupamentoUserDocumentoService->create(["documento_id" => $id,"user_id"  => $user, 'tipo' => 'TREINAMENTO']);
            }

            foreach ($diff_para_detete_user as $key => $user) {
                $this->agrupamentoUserDocumentoService->delete(
                    [
                        ['documento_id','=',$id],
                        ['user_id','=',$user,"AND"],
                        ['tipo', '=', 'TREINAMENTO', "AND"]
                    ]
                );
            }

            /**Cria Agrupamento de Documento (Divulgacao) */
            $grupoDivulgacao = array();
            foreach ($data['grupo_divulgacao'] as $key => $value) {
                array_push($grupoDivulgacao, $value['user_id']);
            }

            $diff_para_create_user_div  = array_diff($grupoDivulgacao, $users);
            $diff_para_detete_user_div = array_diff($users, $grupoDivulgacao);

            foreach ($diff_para_create_user_div as $key => $user) {
                $this->agrupamentoUserDocumentoService->create(["documento_id" => $id,"user_id"  => $user, 'tipo' => 'DIVULGACAO']);
            }

            foreach ($diff_para_detete_user_div as $key => $user) {
                $this->agrupamentoUserDocumentoService->delete(
                    [
                        ['documento_id','=',$id],
                        ['user_id','=',$user,"AND"],
                        ['tipo', '=', 'DIVULGACAO', "AND"]
                    ]
                );
            }


            /**Cria Documento Item Norma */
            $buscaTodosItemNorma = $this->itemNormaRepository->findAll();
            $itensNorma =  array_column(json_decode(json_encode($buscaTodosItemNorma), true), 'id');

            $item = array();
            foreach ($data['item_normas'] as $key => $value) {
                array_push($item, $value['item_norma_id']);
            }

            $diff_para_create_item_norma  = array_diff($item, $itensNorma);
            $diff_para_detete_item_norma = array_diff($itensNorma, $item);

            foreach ($diff_para_create_item_norma as $key => $item) {
                $this->documentoItemNormaService->create(["documento_id" => $id,"item_norma_id"  => $item]);
            }

            foreach ($diff_para_detete_item_norma as $key => $item) {
                $this->documentoItemNormaService->delete(
                    [
                        ['documento_id','=',$id],
                        ['item_norma_id','=',$item,"AND"]
                    ]
                );
            }

            /**Etapa de Aprovação */
            $userEtapaDocumentoDelecao = $this->userEtapaDocumentoRepository->findBy(
                [
                    ['documento_id','=',$id]
                ]
            );
            //Deleta
            foreach ($userEtapaDocumentoDelecao as $key => $value) {
                $this->userEtapaDocumentoService->delete($value->id);
            }
            //Cria
            foreach ($data['etapa_aprovacao'] as $value) {
                $value['documento_id'] = (int) $id;
                dd($value);
                $this->userEtapaDocumentoService->create($value);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function gerarCodigoDocumento($tipoDocumento, $setor)
    {
        $tipoDocumentoRepository = new TipoDocumentoRepository();
        $buscaTipoDocumento = $tipoDocumentoRepository->find($tipoDocumento);
        $codigoPadrao = json_decode($buscaTipoDocumento->codigo_padrao);

        $setorRepository = new SetorRepository();
        $buscaSetor = $setorRepository->find($setor);

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
                        $buscaTipoDocumento->ultimo_documento + 1,
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
                if (strlen($numero) <= 1) $codigo = str_pad($numero, 3, '0', STR_PAD_LEFT);
                elseif (strlen($numero) == 2) $codigo = str_pad($numero, 3, '0', STR_PAD_LEFT);
                else $codigo = $numero;
                break;
            default:
                $valor = $numero + ".01";

                if (strlen($numero) <= 1) $codigo = str_pad($valor, 3, '0', STR_PAD_LEFT);
                elseif (strlen($numero) == 2) $codigo = str_pad($valor, 2, '0', STR_PAD_LEFT);
                else $codigo = $valor;
                break;
        }
        return $codigo;
    }

    public function validador($data)
    {

        $validacao = new ValidacaoService($this->rules, $data->all());
        $errors = $validacao->make();

        if ($errors) {
            return $errors;
        }

        return false;
    }

    public function montaRequestWorkflow($tipoDocumento, $documentoId, $versaoDocumento)
    {
        $etapas = $this->tipoDocumentoService->getEtapasFluxo($tipoDocumento);
        $etapaId = $etapas[0]['id'];

        return [
            "descricao" => 'Documento em elaboração',
            "justificativa" => '',
            "justificativa_lida" => false,
            "documento_id" => $documentoId,
            "etapa_fluxo_id" => $etapaId,
            "user_id" => Auth::user()->id,
            "versao_documento" => $versaoDocumento
        ];
    }
}
