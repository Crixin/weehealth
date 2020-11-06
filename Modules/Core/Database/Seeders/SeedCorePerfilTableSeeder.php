<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\PerfilRepository;

class SeedCorePerfilTableSeeder extends Seeder
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
