<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedDocsCreateParametroStatusEtapaFluxoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //STATUS_ETAPA_FLUXO
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "STATUS_ETAPA_FLUXO";
        $newParametro->descricao = "Status Etapa Fluxo";
        $newParametro->valor_padrao =
        '{
            "1": "EM ELABORACAO",
            "2": "EM APROVACAO",
            "3": "EM TREINAMENTO",
            "4": "FINALIZADO",
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();


        //TIPO_APROVACAO_ETAPA
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "TIPO_APROVACAO_ETAPA";
        $newParametro->descricao = "Tipo Aprovacao Etapa";
        $newParametro->valor_padrao =
        '{
            "1": "SIMPLES",
            "2": "CONDICIONAL"
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
