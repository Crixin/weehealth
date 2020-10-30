<?php

namespace Database\Seeders;

use App\Gerenciador;
use Illuminate\Database\Seeder;

class GerenciadorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gerenciador::create([
            'id_cliente' => 1,
            'nome_cliente' => 'Log20 Logística',
            'obs' => 'Primeiro cliente a utilizar o portal. Possui funcionalidades específicas como o download de um arquivo .zip customizado, a inserção em um FTP, o upload de arquivos por agentes e externos e uma tela de relatório customizada.',
        ]);
    }
}
