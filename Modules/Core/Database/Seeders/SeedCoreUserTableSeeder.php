<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Model\User;

class SeedCoreUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminWeecode = new User();
        $adminWeecode->name = "Weecode";
        $adminWeecode->username = 'weecode';
        $adminWeecode->email = 'suporte@weecode.com.br';
        $adminWeecode->utilizar_permissoes_nivel_usuario = true;
        $adminWeecode->password = bcrypt('Weecode.Adm.Portal');
        $adminWeecode->administrador = true;
        $adminWeecode->perfil_id = 1;
        $adminWeecode->save();
    }
}
