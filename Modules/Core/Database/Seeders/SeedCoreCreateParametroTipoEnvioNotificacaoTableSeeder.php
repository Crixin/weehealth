<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedCoreCreateParametroTipoEnvioNotificacaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoEnvio = new Parametro();
        $tipoEnvio->identificador_parametro = "TIPO_ENVIO_NOTIFICACAO";
        $tipoEnvio->descricao = "Tipo de envio de notificações";
        $tipoEnvio->valor_padrao = '{
            "1": "EMAIL"
        }';
        $tipoEnvio->valor_usuario = "1";
        $tipoEnvio->ativo = true;
        $tipoEnvio->save();
    }
}
