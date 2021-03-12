<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedCoreCreateParametroNotificacaoAprovacao extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prmDownload = new Parametro();
        $prmDownload->identificador_parametro = "NOTIFICACAO_APROVACAO_DOCUMENTO";
        $prmDownload->descricao = "Notificação para aprovação";
        $prmDownload->valor_padrao = '1';
        $prmDownload->valor_usuario = '';
        $prmDownload->ativo = true;
        $prmDownload->save();
    }
}