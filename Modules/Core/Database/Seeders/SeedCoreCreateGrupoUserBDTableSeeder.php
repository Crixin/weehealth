<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Core\Model\User;

class SeedCoreCreateGrupoUserBDTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select("CREATE ROLE weehealth WITH NOLOGIN NOSUPERUSER NOCREATEDB NOCREATEROLE INHERIT NOREPLICATION CONNECTION LIMIT -1");
        DB::unprepared("GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO weehealth");
        DB::unprepared("GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO weehealth");

        foreach (User::all() as $key => $value) {
            $user     = '"' . $value->username . '"';
            $password = $value->password;
            $cria     = DB::select("CREATE ROLE $user WITH LOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION VALID UNTIL 'infinity' ");
            $altera   = DB::unprepared("ALTER USER $user WITH PASSWORD '" . $password . "'");
            $setFrupo = DB::unprepared("GRANT weehealth TO $user ");
        }
    }
}
