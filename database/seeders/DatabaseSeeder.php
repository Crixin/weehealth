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
        /**CORE*/
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCidadeTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreParametroTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCorePerfilTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreSetupTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreUserTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTableSeeder::class);

        /**PORTAL */
        $this->call(\Modules\Portal\Database\Seeders\SeedPortalProcessoTableSeeder::class);

        /**MODULO DOCS */
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroStatusEtapaFluxoTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroTipoControleRegistroTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsSetorTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsTipoDocumentoTableSeeder::class);
    }
}
