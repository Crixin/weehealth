<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedDocsCreateParametroNivelAcessoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //NIVEL ACESSO
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "NIVEL_ACESSO";
        $newParametro->descricao = "Níveis de Acesso";
        $newParametro->valor_padrao =
        '{
            "1": "Confidencial",
            "2": "Restrito",
            "3": "Livre"
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();

        //CLASSIFICACAO
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "CLASSIFICACAO";
        $newParametro->descricao = "Classificação do Documento";
        $newParametro->valor_padrao =
        '{
            "1": "Qualidade",
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
