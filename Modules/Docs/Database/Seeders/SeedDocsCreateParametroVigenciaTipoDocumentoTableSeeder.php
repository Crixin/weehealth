<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedDocsCreateParametroVigenciaTipoDocumentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //PERIODO DE VIGENCIA
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "PERIODO_VIGENCIA";
        $newParametro->descricao = "Período de Vigência do Documento";
        $newParametro->valor_padrao =
        '[
            {
              "id":1,
              "descricao":"Mês",
              "numero_dias":30
            },
            {
              "id":2,
              "descricao":"Bimestre",
              "numero_dias":60
            }
        ]';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();



        //PERIODO AVISO DE VENCIMENTO
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "PERIODO_AVISO_VENCIMENTO";
        $newParametro->descricao = "Período de aviso de vencimento";
        $newParametro->valor_padrao =
        '[
            {
              "id":1,
              "descricao":"30 Dias Antes",
              "numero_dias":30
            },
            {
              "id":2,
              "descricao":"60 Dias Antes",
              "numero_dias":60
            }
        ]';
        $newParametro->valor_usuario = 1;
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
