<?php

namespace Modules\Portal\Database\Seeders;

use App\Processo;
use Illuminate\Database\Seeder;

class ProcessoTableSeeder extends Seeder
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
