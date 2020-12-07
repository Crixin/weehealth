<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DocsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroStatusEtapaFluxoTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroTipoControleRegistroTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroVigenciaTipoDocumentoTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsSetorTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsTipoDocumentoTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsTipoSetorTableSeeder::class);
    }
}
