<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedCoreCreateParametroOrgaoReguladorTableSeeder extends Seeder
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
    }
}
