<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Repositories\PerfilRepository;

class PerfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perfilRepository = new PerfilRepository();

        $perfilRepository->create([
            'id' => 1,
            'nome' => 'Weecode'
        ]);
    }
}
