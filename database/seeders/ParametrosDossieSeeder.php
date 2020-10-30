<?php

namespace Database\Seeders;

use App\Parametro;
use Illuminate\Database\Seeder;

class ParametrosDossieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prmDownload = new Parametro();
        $prmDownload->identificador_parametro = "TEMP_DOSSIE_ACESSO";
        $prmDownload->descricao = "Tempo de expiração do email do dossiê de documentos";
        $prmDownload->valor_padrao = 15;
        $prmDownload->valor_usuario = "";
        $prmDownload->ativo = true;
        $prmDownload->save();
        
        $prmVisualizar = new Parametro();
        $prmVisualizar->identificador_parametro = "POC_HAVAN";
        $prmVisualizar->descricao = "POC Havan";
        $prmVisualizar->valor_padrao = true;
        $prmVisualizar->valor_usuario = "";
        $prmVisualizar->ativo = true;
        $prmVisualizar->save();

        $prmVisualizar = new Parametro();
        $prmVisualizar->identificador_parametro = "TIME_PRORROG_DOSSIE";
        $prmVisualizar->descricao = "Tempo de duração do token de acesso do reenvio e duração do .zip no servidor (O valor é definido por dias)";
        $prmVisualizar->valor_padrao = 5;
        $prmVisualizar->valor_usuario = "";
        $prmVisualizar->ativo = true;
        $prmVisualizar->save();
    }
}
