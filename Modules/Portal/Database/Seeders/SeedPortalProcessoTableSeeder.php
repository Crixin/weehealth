<?php

namespace Modules\Portal\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Portal\Model\Processo;

class SeedPortalProcessoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Processo::create([
            'nome' => 'Documentos Diversos',
            'descricao' => 'Processo utilizado para permitir o UPLOAD de documentos externos ao sistema. Apenas grupos e usuários com a permissão específica poderão executar tal ação.'
        ]);
    }
}
