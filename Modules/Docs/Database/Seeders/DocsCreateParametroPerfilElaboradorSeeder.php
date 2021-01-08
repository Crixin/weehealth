<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class DocsCreateParametroPerfilElaboradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TIPO_APROVACAO_ETAPA
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "PERFIL_ELABORADOR";
        $newParametro->descricao = "Id do perfil elaborador";
        $newParametro->valor_padrao = 2;
        $newParametro->valor_usuario = "";
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
