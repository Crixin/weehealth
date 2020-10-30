<?php

use App\User;
use App\Classes\Constants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddAdministratorColumnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('administrador')->default(false);
        });


        // Usuário reservado para o suporte do sistema (Weecode) e um superAdmin do cliente
        $superAdmins = User::whereIn('id', Constants::$ARR_SUPER_ADMINISTRATORS_ID)->get();
        foreach ($superAdmins as $superAdmin) {
            $superAdmin->administrador = true;
            $superAdmin->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('administrador');
        });
    }
}
