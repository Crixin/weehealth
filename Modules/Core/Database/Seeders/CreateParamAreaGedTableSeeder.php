<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class CreateParamAreaGedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoEnvio = new Parametro();
        $tipoEnvio->identificador_parametro = "AREA_GED_DOCUMENTOS";
        $tipoEnvio->descricao = "Área do GED onde ficarão os documentos";
        $tipoEnvio->valor_padrao = 'VAZIO';
        $tipoEnvio->valor_usuario = null;
        $tipoEnvio->ativo = true;
        $tipoEnvio->save();
    }
}
