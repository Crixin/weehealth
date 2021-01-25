<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCoreNotificacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_notificacao', function (Blueprint $table) {
            $table->integer('tempo_delay_envio')->unsigned()->nullable()->default('0');
            $table->integer('numero_tentativas_envio')->unsigned()->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_notificacao', function (Blueprint $table) {
            $table->dropColumn('tempo_delay_envio');
            $table->dropColumn('numero_tentativas_envio');
        });
    }
}
