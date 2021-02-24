<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CoreDatabaseSeeder extends Seeder
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
        $this->call(\Modules\Core\Database\Seeders\SeedCoreSetupTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreUserTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreSetorTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTipoEnvioNotificacaoTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTipoNotificacaoTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateGrupoUserBDTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateFunctionInsereLogTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\CreateParamAreaGedTableSeeder::class);
    }
}
