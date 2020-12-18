<?php

namespace Modules\Docs\Services;

use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\SetorRepository;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\TipoDocumentoRepository;

class DocumentoService
{
    protected $documentoRepository;

    public static function create($request)
    {
        dd('metodo cria');
        //$criaDocumento = new DocumentoRepository();
        //return $criaDocumento->create($request);

        /*
            #Documentos Pai
            if (!empty($request->documentoPai)) {
                foreach (json_decode($request->documentoPai) as $key => $valueDocumentoPai) {
                    $montaRequestDocumentoPai = [
                        'documento_id'     =>  $documentoCriado->id,
                        'documento_pai_id' => $valueDocumentoPai
                    ];
                    $this->documentoPairepository->create($montaRequestDocumentoPai);
                }
            }

            #Documentos Vinculados 
            if (!empty($request->documentoVinculado)) {
                foreach (json_decode($request->documentoVinculado) as $key => $valueDocumentosVinculados) {
                    $montaRequestDocumentosVinculados = [
                        'documento_id'           =>  $documentoCriado->id,
                        'documento_vinculado_id' => $valueDocumentosVinculados
                    ];
                    $this->documentoVinculadoRepository->create($montaRequestDocumentosVinculados);
                }
            }

            #Grupo Treinamento 
            if (!empty($request->grupoTreinamentoDoc)) {
                foreach (json_decode($request->grupoTreinamentoDoc) as $key => $valueUserTreinamento) {
                    $montaRequestTreinamento = [
                        "tipo" => 'TREINAMENTO',
                        "documento_id" => $documentoCriado->id,
                        "user_id" => $valueUserTreinamento
                    ];
                    $this->agrupamentoUserDocumentoRepository->create($montaRequestTreinamento);
                }
            }

            #Grupo Divulgacao
            if (!empty($request->grupoDivulgacaoDoc)) {
                foreach (json_decode($request->grupoDivulgacaoDoc) as $key => $valueUserDivulgacao) {
                    $montaRequestDivulgacao = [
                        "tipo" => 'DIVULGACAO',
                        "documento_id" => $documentoCriado->id,
                        "user_id" => $valueUserDivulgacao
                    ];
                    $this->agrupamentoUserDocumentoRepository->create($montaRequestDivulgacao);
                }
            }

            #Normas 
            if (!empty($request->grupoNorma)) {
                foreach (json_decode($request->grupoNorma) as $key => $valueNormas) {
                    $montaRequestNorma = [
                        "documento_id" => $documentoCriado->id,
                        "item_norma_id" => $valueNormas
                    ];
                    $this->documentoItemNormaRepository->create($montaRequestNorma);
                }
            }

            #Etapas de Aprovacao
            $tipoDocumentoService = new TipoDocumentoService();
            $etapas = $tipoDocumentoService->getEtapasFluxosPorComportamento(
                $request->tipoDocumento,
                'comportamento_aprovacao'
            );
            foreach ($etapas as $key => $value) {
                $variavel = 'grupo' . $value['nome'];
                if (!empty($request->$variavel)) {
                    foreach (json_decode($request->$variavel) as $key => $idAprovadores) {
                        $montaRequestEtapa = [
                            "user_id" => $idAprovadores,
                            "etapa_id" => $value['id'],
                            "documento_id" => $documentoCriado->id
                        ];
                        $this->userEtapaDocumentoRepository->create($montaRequestEtapa);
                    }
                }
            }
        
        */


    }

    public static function gerarCodigoDocumento($tipoDocumento, $setor)
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
                    $codigoFinal .= self::gerarPadraoNumero(
                        $buscaSetor->ultimo_codigo + 1,
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

    public static function gerarPadraoNumero($numero, $padrao)
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
}
