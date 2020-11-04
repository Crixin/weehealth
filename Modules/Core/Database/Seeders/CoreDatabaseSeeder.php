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
        Model::unguard();

        $this->call(Modules\Core\Database\Seeders\CidadeTableSeeder::class);
        $this->call(Modules\Core\Database\Seeders\PermissoesTableSeeder::class);
        $this->call(Modules\Core\Database\Seeders\UsersTableSeeder::class);
        $this->call(Modules\Core\Database\Seeders\CidadeTableSeeder::class);
        $this->call(Modules\Core\Database\Seeders\ParametroTableSeeder::class);
        $this->call(Modules\Core\Database\Seeders\SetupTableSeeder::class);
        $this->call(Modules\Core\Database\Seeders\ParametroDossieSeeder::class);
    }
}
