<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PerfilTableSeeder::class);
        $this->call(PermissoesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CidadeTableSeeder::class);
        $this->call(ParametroTableSeeder::class);
        $this->call(GerenciadorTableSeeder::class);
        $this->call(ProcessoTableSeeder::class);
        $this->call(SetupTableSeeder::class);
        $this->call(ParametrosDossieSeeder::class);
    }
}
