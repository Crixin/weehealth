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
            "LOCAL_ARMAZENAMENTO": "Armazenamento",
            "DISPOSICAO": "Disposição",
            "MEIO_DISTRIBUICAO": "Meio de Distribuição",
            "MEIO_PROTECAO": "Proteção",
            "RECUPERACAO": "Recuperação",
            "TEMPO_RETENCAO_DEPOSITO": "Retenção Mínima - Arquivo Morto",
            "TEMPO_RETENCAO_LOCAL": "Retenção Mínima - Local"
        }';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
