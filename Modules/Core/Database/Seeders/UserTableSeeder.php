<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
