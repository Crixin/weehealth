<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedCoreCreateParametroValidadeDocumentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prmDownload = new Parametro();
        $prmDownload->identificador_parametro = "NOTIFICACAO_VALIDADE_DOCUMENTO";
        $prmDownload->descricao = "Notificação para validade do documento";
        $prmDownload->valor_padrao = '1';
        $prmDownload->valor_usuario = '';
        $prmDownload->ativo = true;
        $prmDownload->save();
    }
}
