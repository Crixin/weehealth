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

        //ORGAO REGULADOR
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "ORGAO_REGULADOR";
        $newParametro->descricao = "Órgãos reguladores";
        $newParametro->valor_padrao =
        '{
            "1": "ISO",
            "2": "ONA"
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();

        //CICLO DE AUDITORIA
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "CICLO_AUDITORIA";
        $newParametro->descricao = "Ciclo de Auditoria";
        $newParametro->valor_padrao =
        '{
            "1": "Mensal",
            "2": "Bimestre",
            "3": "Trimestre",
            "4": "Semestre",
            "5": "Anual"
          }';
        $newParametro->valor_usuario = "5";
        $newParametro->ativo = true;
        $newParametro->save();

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
