<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedCoreCreateParametroTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prmDownload = new Parametro();
        $prmDownload->identificador_parametro = "TIPO_EMPRESA";
        $prmDownload->descricao = "Tipos de empresas.";
        $prmDownload->valor_padrao = "CLIENTE;FORNECEDOR;PARCEIRO;TRANSPORTADORA";
        $prmDownload->valor_usuario = "CLIENTE";
        $prmDownload->ativo = true;
        $prmDownload->save();
    }
}
