<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedDocsCreateParametroTipoControleRegistroTableSeeder extends Seeder
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
        $newParametro->identificador_parametro = "TIPO_CONTROLE_REGISTRO";
        $newParametro->descricao = "Tipo Controle de Registro";
        $newParametro->valor_padrao =
        '{
            "1": "Armazenamento",
            "2": "Disposição",
            "3": "Meio",
            "4": "Proteção",
            "5": "Recuperação",
            "6": "Retenção Mínima - Arquivo Morto",
            "7": "Retenção Mínima - Local"
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
