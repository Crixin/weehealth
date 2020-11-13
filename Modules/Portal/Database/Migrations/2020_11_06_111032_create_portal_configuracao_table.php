<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortalConfiguracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portal_configuracao_tarefa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('tipo');
            $table->string('caminho');
            $table->string('ip');
            $table->integer('porta');
            $table->string('usuario');
            $table->string('senha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portal_configuracao_tarefa');
    }
}
