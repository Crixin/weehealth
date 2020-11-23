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
        $newParametro->valor_padrao = "ISO;ONA";
        $newParametro->valor_usuario = "ONA";
        $newParametro->ativo = true;
        $newParametro->save();

        //CICLO DE AUDITORIA
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "CICLO_AUDITORIA";
        $newParametro->descricao = "Ciclo de Auditoria";
        $newParametro->valor_padrao = "";
        $newParametro->valor_usuario = "";
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
