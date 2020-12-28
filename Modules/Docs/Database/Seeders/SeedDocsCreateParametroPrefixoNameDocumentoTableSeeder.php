<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedDocsCreateParametroPrefixoNameDocumentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //PREFIXO_TITULO_DOCUMENTO
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "PREFIXO_TITULO_DOCUMENTO";
        $newParametro->descricao = "Prefixo utilizado na formação do título do documento";
        $newParametro->valor_padrao =
        '_rev';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
