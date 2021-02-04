<?php

namespace Modules\Docs\Services;

use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
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
    UserEtapaDocumentoRepository,
    VinculoDocumentoRepository
};
use Modules\Docs\Services\WorkflowService;

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


    protected $rules;

    public function __construct(
        Documento $documento,
        UserRepository $userRepository,
        DocumentoRepository $documentoRepository,
        ItemNormaRepository $itemNormaRepository,
        TipoDocumentoRepository $tipoDocumentoRepository,
        VinculoDocumentoRepository $vinculoDocumentoRepository,
        UserEtapaDocumentoRepository $userEtapaDocumentoRepository,
        DocumentoItemNormaRepository $documentoItemNormaRepository,
        HierarquiaDocumentoRepository $hierarquiaDocumentoRepository,
        AgrupamentoUserDocumentoRepository $agrupamentoUserDocumentoRepository
    ) {
        $this->rules = $documento->rules;

        $this->userRepository = $userRepository;
        $this->documentoRepository = $documentoRepository;
        $this->itemNormaRepository = $itemNormaRepository;
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
        $this->vinculoDocumentoRepository = $vinculoDocumentoRepository;
        $this->documentoItemNormaRepository = $documentoItemNormaRepository;
        $this->userEtapaDocumentoRepository = $userEtapaDocumentoRepository;
        $this->hierarquiaDocumentoRepository = $hierarquiaDocumentoRepository;
        $this->agrupamentoUserDocumentoRepository = $agrupamentoUserDocumentoRepository;
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
                $hierarquiaDocumentoService = new HierarquiaDocumentoService();
                $vinculoDocumentoService = new VinculoDocumentoService();
                $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();
                $userEtapaDocumentoService = new UserEtapaDocumentoService();
                $documentoItemNormaService = new DocumentoItemNormaService();
                $tipoDocumentoService = new TipoDocumentoService();


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
                foreach ($data['hierarquia_documento'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $resp = $hierarquiaDocumentoService->create($value);
                }

                /**Cria Vinculo de Documento */
                foreach ($data['vinculo_documento'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $vinculoDocumentoService->create($value);
                }

                /**Cria Agrupamento de Documento (Treinamento) */
                foreach ($data['grupo_treinamento'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $value['tipo'] = 'TREINAMENTO';
                    $agrupamentoUserDocumentoService->create($value);
                }

                /**Cria Agrupamento de Documento (Divulgacao) */
                foreach ($data['grupo_divulgacao'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $value['tipo'] = 'DIVULGACAO';
                    $agrupamentoUserDocumentoService->create($value);
                }

                /**Cria Documento Item Norma */
                foreach ($data['item_normas'] ?? [] as $value) {
                    $value['documento_id'] = $documento->id;
                    $documentoItemNormaService->create($value);
                }

                /**Etapa de Aprovação */
                foreach ($data['etapa_aprovacao'] as $value) {
                    $value['documento_id'] = $documento->id;
                    $value['documento_revisao'] = "00";
                    $userEtapaDocumentoService->create($value);
                }

                $tipoDocumentoService->atualizaUltimoCodigoTipoDocumento($data['tipo_documento_id']);


                return $documento;
            });
            return response()->json(["success" => true, "data" => ['documento_id' => $documento->id]]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(["success" => false]);
        }
    }

    public function update($data, $id)
    {
        try {
            $hierarquiaDocumentoService = new HierarquiaDocumentoService();
            $vinculoDocumentoService = new VinculoDocumentoService();
            $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();
            $userEtapaDocumentoService = new UserEtapaDocumentoService();
            $documentoItemNormaService = new DocumentoItemNormaService();
            
            $this->rules['tituloDocumento'] .= "," . $id;
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

            $documento = $this->documentoRepository->find($id);
            
            $this->documentoRepository->update($updateDocumento, $id);

            /**Cria Hierarquia Documento */
            $buscaTodosDocumentos = $this->hierarquiaDocumentoRepository->findBy(
                [
                    ['documento_id', '=', $id]
                ]
            );

            $documentos =  array_column(json_decode(json_encode($buscaTodosDocumentos), true), 'documento_pai_id');
            $hierarquia = [];

            foreach ($data['hierarquia_documento'] as $key => $value) {
                array_push($hierarquia, $value['documento_pai_id']);
                $hierarquiaDocumentoService->firstOrCreate(
                    [
                        "documento_id" => $id,
                        "documento_pai_id"  => $value['documento_pai_id']
                    ]
                );
            }

            $diff_para_delete = array_diff($documentos, $hierarquia);
            $idDelete = [];

            foreach ($diff_para_delete as $key => $doc) {
                $busca = $this->hierarquiaDocumentoRepository->findOneBy(
                    [
                        ['documento_id','=',$id],
                        ['documento_pai_id','=',$doc,"AND"]
                    ]
                );
                array_push($idDelete, $busca->id);
            }
            $hierarquiaDocumentoService->delete($idDelete, 'id');

            /**Cria Vinculo de Documento */
            $buscaTodosDocumentosVinculados = $this->vinculoDocumentoRepository->findBy(
                [
                    ['documento_id', '=', $id]
                ]
            );
            $documentos =  array_column(json_decode(json_encode($buscaTodosDocumentosVinculados), true), 'documento_vinculado_id');
            $vinculado = array();
            foreach ($data['vinculo_documento'] as $key => $value) {
                array_push($vinculado, $value['documento_vinculado_id']);
                $vinculoDocumentoService->firstOrCreate(
                    [
                        "documento_id" => $id,
                        "documento_vinculado_id"  => $value['documento_vinculado_id']
                    ]
                );
            }

            $diff_para_detete_vinculo = array_diff($documentos, $vinculado);
            $idDelete = [];
            foreach ($diff_para_detete_vinculo as $key => $doc) {
                $busca = $this->vinculoDocumentoRepository->findOneBy(
                    [
                        ['documento_id','=',$id],
                        ['documento_vinculado_id','=',$doc,"AND"]
                    ]
                );
                array_push($idDelete, $busca->id);
            }
            $vinculoDocumentoService->delete($idDelete, 'id');

            /**Cria Agrupamento de Documento (Treinamento) */
            $buscaTodosUsuarios = $this->agrupamentoUserDocumentoRepository->findBy(
                [
                    ["documento_id", "=", $id],
                    ["tipo", "=", "TREINAMENTO", "AND"]
                ]
            );
            $users = [];
            foreach ($buscaTodosUsuarios as $key => $value) {
                array_push($users, $value['grupo_id'] . '-' . $value['user_id']);
            }
            $grupoTreinamento = array();
            foreach ($data['grupo_treinamento'] as $key => $value) {
                $agrupamentoUserDocumentoService->firstOrCreate(
                    [
                        "documento_id" => $id,
                        "user_id"  => $value['user_id'],
                        "grupo_id" => $value['grupo_id'],
                        'tipo' => 'TREINAMENTO'
                    ]
                );
                array_push($grupoTreinamento, $value['grupo_id'] . '-' . $value['user_id']);
            }
            $diff_para_detete_user = array_diff($users, $grupoTreinamento);
            $idDelete = [];
            foreach ($diff_para_detete_user as $key => $delete) {
                $busca = $this->agrupamentoUserDocumentoRepository->findOneBy(
                    [
                        ['documento_id','=',$id],
                        ['user_id','=',explode('-', $delete)[1],"AND"],
                        ['grupo_id','=',explode('-', $delete)[0],"AND"],
                        ['tipo', '=', 'TREINAMENTO', "AND"]
                    ]
                );
                array_push($idDelete, $busca->id);
            }
            if (!empty($idDelete)) {
                $agrupamentoUserDocumentoService->delete($idDelete, 'id');
            }

            /**Cria Agrupamento de Documento (Divulgacao) */
            $buscaTodosUsuarios = $this->agrupamentoUserDocumentoRepository->findBy(
                [
                    ["documento_id", "=", $id],
                    ["tipo", "=", "DIVULGACAO", "AND"]
                ]
            );
            $users = [];
            foreach ($buscaTodosUsuarios as $key => $value) {
                array_push($users, $value['grupo_id'] . '-' . $value['user_id']);
            }

            $grupoDivulgacao = array();
            foreach ($data['grupo_divulgacao'] as $key => $value) {
                $agrupamentoUserDocumentoService->firstOrCreate(
                    [
                        "documento_id" => $id,
                        "user_id"  => $value['user_id'],
                        "grupo_id" => $value['grupo_id'],
                        'tipo' => 'DIVULGACAO'
                    ]
                );
                array_push($grupoDivulgacao, $value['grupo_id'] . '-' . $value['user_id']);
            }

            $diff_para_detete_user_div = array_diff($users, $grupoDivulgacao);
            $idDelete = [];
            foreach ($diff_para_detete_user_div as $key => $user) {
                $busca = $this->agrupamentoUserDocumentoRepository->findOneBy(
                    [
                        ['documento_id','=',$id],
                        ['user_id','=',$user,"AND"],
                        ['tipo', '=', 'DIVULGACAO', "AND"]
                    ]
                );
                array_push($idDelete, $busca->id);
            }
            if (!empty($idDelete)) {
                $agrupamentoUserDocumentoService->delete($idDelete, 'id');
            }


            /**Cria Documento Item Norma */
            $buscaTodosItemNorma = $this->documentoItemNormaRepository->findBy(
                [
                    ['documento_id','=',$id]
                ]
            );
            $itensNorma =  array_column(json_decode(json_encode($buscaTodosItemNorma), true), 'item_norma_id');

            $item = array();
            foreach ($data['item_normas'] as $key => $value) {
                array_push($item, $value['item_norma_id']);
            }

            $diff_para_create_item_norma  = array_diff($item, $itensNorma);
            $diff_para_detete_item_norma = array_diff($itensNorma, $item);
            foreach ($diff_para_create_item_norma as $key => $item) {
                $documentoItemNormaService->create(["documento_id" => $id,"item_norma_id"  => $item]);
            }

            $idDelete = [];
            foreach ($diff_para_detete_item_norma as $key => $item) {
                $busca = $this->documentoItemNormaRepository->findOneBy(
                    [
                        ['documento_id','=',$id],
                        ['item_norma_id','=',$item,"AND"]
                    ]
                );
                array_push($idDelete, $busca->id);
            }
            $documentoItemNormaService->delete($idDelete, 'id');

            /**Etapa de Aprovação */
            $userEtapaDocumentoDelecao = $this->userEtapaDocumentoRepository->findBy(
                [
                    ['documento_id','=',$id]
                ]
            );
            //Deleta
            foreach ($userEtapaDocumentoDelecao as $key => $value) {
                $userEtapaDocumentoService->delete($value->id);
            }
            //Cria
            foreach ($data['etapa_aprovacao'] as $value) {
                $value['documento_id'] = (int) $id;
                $value['documento_revisao'] = $documento->revisao;
 
                $userEtapaDocumentoService->create($value);
            }
            return true;
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }

    public function gerarCodigoDocumento($tipoDocumento, $setor)
    {
        $buscaTipoDocumento = $this->tipoDocumentoRepository->find($tipoDocumento);
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
}
