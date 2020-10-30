<?php

namespace Database\Seeders;

use App\Cidade;
use Illuminate\Database\Seeder;

class CidadeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        
        /**
         * ======================================================================
         *                          PRODUÇÃO
         * ======================================================================
         */
        
        $client = new \GuzzleHttp\Client();
        $request = $client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
        $contents = $request->getBody()->getContents();
    
        $ids = array();
        $arr = json_decode($contents, true);
        foreach ($arr as $key => $value) {
            $ids[] = $value["id"];
        }

        foreach ($ids as $key => $value) {
            $request = $client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $value . '/municipios');
            $contents = $request->getBody()->getContents();
            
            $arr = json_decode($contents, true);
            foreach ($arr as $key => $cidade) {
                $c = new Cidade();
                $c->nome = $cidade["nome"];
                $c->estado = $cidade["microrregiao"]["mesorregiao"]["UF"]["nome"];
                $c->sigla_estado = $cidade["microrregiao"]["mesorregiao"]["UF"]["sigla"];
                $c->save();
            }
        }


        // OU

        /**
         * ======================================================================
         *                          TESTES RÁPIDOS
         * ======================================================================
         */
        /*
        $c = new Cidade();
        $c->nome = "Erechim";
        $c->estado = "Rio Grande do Sul";
        $c->sigla_estado = "RS";
        $c->save();

        $c2 = new Cidade();
        $c2->nome = "Rio de Janeiro";
        $c2->estado = "Rio de Janeiro";
        $c2->sigla_estado = "RJ";
        $c2->save();

        $c3 = new Cidade();
        $c3->nome = "São Paulo";
        $c3->estado = "São Paulo";
        $c3->sigla_estado = "SP";
        $c3->save();
        */

    }
}
