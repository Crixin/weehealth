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
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCidadeTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreParametroTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCorePerfilTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCorePermissaoTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreSetupTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreUserTableSeeder::class);
        $this->call(\Modules\Portal\Database\Seeders\SeedPortalProcessoTableSeeder::class);
    }
}
