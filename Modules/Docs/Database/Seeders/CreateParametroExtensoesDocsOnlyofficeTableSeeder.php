<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class CreateParametroExtensoesDocsOnlyofficeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "EXTENSAO_DOCUMENTO_ONLYOFFICE";
        $newParametro->descricao = "Extensões de documentos aceitas para criar novos documentos";
        $newParametro->valor_padrao = '.doc, .docx, .xls, .xlsx, .odt';
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
