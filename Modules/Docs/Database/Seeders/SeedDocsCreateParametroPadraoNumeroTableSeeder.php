<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\Parametro;

class SeedDocsCreateParametroPadraoNumeroTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //PADRAO CRIACAO DO CODIGO DO DOCUMENTO
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "PADRAO_NUMERO";
        $newParametro->descricao = "Padrão Número";
        $newParametro->valor_padrao =
        '{
          "1": 
         {
            "ID": "1",
            "DESCRICAO": "0",
            "GERADO" : "1, 2, 3...."
          },
          "2":{
            "ID": "2",
            "DESCRICAO": "00",
            "GERADO" : "01, 02, 03...."
          },
         "3":{
            "ID": "3",
            "DESCRICAO": "000",
            "GERADO" : "001, 002, 003...."
          },
         "4":{
            "ID": "4",
            "DESCRICAO": "0000",
            "GERADO" : "001.01, 002.01, 003.01...."
          }
        }';
        $newParametro->valor_usuario = 
        '{
          "1": 
         {
            "ID": "1",
            "DESCRICAO": "0",
            "GERADO" : "1, 2, 3...."
          },
          "2":{
            "ID": "2",
            "DESCRICAO": "00",
            "GERADO" : "01, 02, 03...."
          },
         "3":{
            "ID": "3",
            "DESCRICAO": "000",
            "GERADO" : "001, 002, 003...."
          },
         "4":{
            "ID": "4",
            "DESCRICAO": "0000",
            "GERADO" : "001.01, 002.01, 003.01...."
          }
        }';
        $newParametro->ativo = true;
        $newParametro->save();


        //FORMATO DO CODIGO 
        $newParametro = new Parametro();
        $newParametro->identificador_parametro = "PADRAO_CODIGO";
        $newParametro->descricao = "Padrão da formação do código do documento";
        $newParametro->valor_padrao =
        '{
          "1": {
            "ID": "1",
            "DESCRICAO": "SIGLA",
            "VARIAVEL" : "SIGLA"
          },
          "2": {
            "ID": "2",
            "DESCRICAO": "NÚMERO",
            "VARIAVEL" : "NUMEROPADRAO"
          },
          "3": {
            "ID": "3",
            "DESCRICAO": "SETOR",
            "VARIAVEL" : "SETOR"
          },
          "4": {
            "ID": "4",
            "DESCRICAO": "- ",
            "VARIAVEL" : "SEPARADOR"
          },
          "5": {
            "ID": "5",
            "DESCRICAO": " -",
            "VARIAVEL" : "SEPARADOR"
          }
        }';
        $newParametro->valor_usuario = 
        '{
          "1": {
            "ID": "1",
            "DESCRICAO": "SIGLA",
            "VARIAVEL" : "SIGLA"
          },
          "2": {
            "ID": "2",
            "DESCRICAO": "NÚMERO",
            "VARIAVEL" : "NUMEROPADRAO"
          },
          "3": {
            "ID": "3",
            "DESCRICAO": "SETOR",
            "VARIAVEL" : "SETOR"
          },
          "4": {
            "ID": "4",
            "DESCRICAO": "- ",
            "VARIAVEL" : "SEPARADOR"
          },
          "5": {
            "ID": "5",
            "DESCRICAO": " -",
            "VARIAVEL" : "SEPARADOR"
          }
        }';
        $newParametro->ativo = true;
        $newParametro->save();
    }
}
