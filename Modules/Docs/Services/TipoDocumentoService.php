<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\TipoDocumentoRepository;

class TipoDocumentoService
{
    protected $tipoDocumentoRepository;

    public function __construct(TipoDocumentoRepository $tipoDocumentoRepository)
    {
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
    }

    public function getEtapasFluxo($idTipoDocumento)
    {
        $buscaTipoDocumento = $this->tipoDocumentoRepository->findOneBy(
            [
                ['ativo', '=', true],
                ['id', '=', $idTipoDocumento, "AND"]
            ]
        );
        foreach ($buscaTipoDocumento->docsFluxo->docsEtapaFluxo as $keyEtapa => $value2) {
            $etapas[$keyEtapa] =
            [
                'id'    => $value2->id,
                'nome'  => ucfirst($value2->nome),
                'ordem' => $value2->ordem,
                'obrigatorio' => $value2->obrigatorio
            ];
        }
        $this->ordenacaoArray($etapas, 'ordem');
        return $etapas;
    }

    public function getEtapasFluxosPorComportamento($idTipoDocumento, $comportamento)
    {
        $buscaTipoDocumento = $this->tipoDocumentoRepository->findOneBy(
            [
                ['ativo', '=', true],
                ['id', '=', $idTipoDocumento, "AND"]
            ]
        );

        $i = 0;
        foreach ($buscaTipoDocumento->docsFluxo->docsEtapaFluxo as $key => $value2) {
            if ($value2->$comportamento == true) {
                $etapas[$i] =
                [
                    'id'    => $value2->id,
                    'nome'  => ucfirst($value2->nome),
                    'ordem' => $value2->ordem,
                    'obrigatorio' => $value2->obrigatorio
                ];
                $i++;
            }
        }
        $this->ordenacaoArray($etapas, 'ordem');
        return $etapas;
    }

    public function atualizaUltimoCodigoTipoDocumento($tipoDocumentoId)
    {
        $buscaUltimoCodigo = $this->tipoDocumentoRepository->find($tipoDocumentoId);
        $request = [
            "ultimo_documento" => $buscaUltimoCodigo->ultimo_documento + 1
        ];
        return $this->update($request, $tipoDocumentoId);
    }

    public function update(array $data, int $id)
    {
        return $this->tipoDocumentoRepository->update($data, $id);
    }

    public function ordenacaoArray(&$array, $column, $direction = SORT_ASC)
    {
        $reference_array = array();
        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }
        array_multisort($reference_array, $direction, $array);
    }
}
