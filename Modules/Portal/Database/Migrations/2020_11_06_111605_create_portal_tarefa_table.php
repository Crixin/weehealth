<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortalTarefaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portal_tarefa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('configuracao_id')->unsigned();
            $table->foreign('configuracao_id')->references('id')->on('portal_configuracao_tarefa');
            $table->string('pasta');
            $table->string('identificador', 1);
            $table->string('area');
            $table->integer('tipo_indexacao');
            $table->json('indices');
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
        Schema::dropIfExists('portal_tarefa');
    }
}
