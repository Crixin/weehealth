<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\TipoDocumentoRepository;

class TipoDocumentoService
{

    public static function getEtapasFluxosPorComportamento($idTipoDocumento, $comportamento)
    {
        $tipoDocumento = new TipoDocumentoRepository();
        $buscaTipoDocumento = $tipoDocumento->findBy(
            [
                ['ativo', '=', true],
                ['id', '=', $idTipoDocumento, "AND"]
            ]
        );
        foreach ($buscaTipoDocumento as $key => $value) {
            $i = 0;
            foreach ($value->docsFluxo->docsEtapaFluxo as $key => $value2) {
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
        }
        return $etapas;
    }
}
