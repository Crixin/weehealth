<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedCoreCreateParametroTipoNotificacaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoEnvio = new Parametro();
        $tipoEnvio->identificador_parametro = "TIPO_NOTIFICACAO";
        $tipoEnvio->descricao = "Tipo de notificações";
        $tipoEnvio->valor_padrao = '{
            "1": "VALIDADE DO DOCUMENTO",
            "2": "DOCUMENTO PUBLICADO",
            "3": "DOCUMENTO COM CÓPIA CONTROLADA",
            "4": "DOCUMENTO VENCIDO",
            "5": "DOCUMENTO QUE PRECISA DE VERIFICAÇÃO",
            "6": "REJEIÇÃO DO DOCUMENTO",
            "7": "APROVAÇÃO DO DOCUMENTO",
            "8": "USUÁRIO DESATIVADO" 
          }';
        $tipoEnvio->valor_usuario = '';
        $tipoEnvio->ativo = true;
        $tipoEnvio->save();
    }
}
