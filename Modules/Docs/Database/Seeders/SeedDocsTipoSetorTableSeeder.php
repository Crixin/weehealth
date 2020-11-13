<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedDocsTipoSetorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setor       = new TipoSetor();
        $setor->nome = "Setor da Empresa";
        $setor->save();
        
        $diretoria       = new TipoSetor();
        $diretoria->nome = "Diretoria";
        $diretoria->save();
        
        $gerencia       = new TipoSetor();
        $gerencia->nome = "Gerência";
        $gerencia->save();
    }
}
