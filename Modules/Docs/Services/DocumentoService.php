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
        $criaDocumento = new DocumentoRepository();
        return $criaDocumento->create($request);
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
                case '$SIGLA':
                    $codigoFinal .= trim($buscaTipoDocumento->sigla);
                    break;
                case '$NUMEROPADRAO':
                    $codigoFinal .= self::gerarPadraoNumero(
                        $buscaSetor->ultimo_codigo + 1,
                        $buscaTipoDocumento->numero_padrao
                    );
                    break;

                case '$SETOR':
                    $codigoFinal .= trim($buscaSetor->sigla);
                    break;

                case '$SEPARADOR':
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
            case '1':
                $codigo = $numero;
                break;
            case '2':
                $codigo = ( strlen($numero) <= 1 ) ? str_pad($numero, 2, '0', STR_PAD_LEFT) : $numero;
                break;
            case '3':
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
