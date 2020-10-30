<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idArea', 50);
            $table->string('idRegistro', 50);
            $table->string('idDocumento', 50)->nullable();
            $table->string('acao', 50);
            $table->string('referencia', 50);
            $table->integer('idUsuario');
            $table->timestamp('data', 50);
            $table->string('nomeProcesso', 250);
            $table->string('descricao', 250);
            $table->string('complemento', 250)->nullable();
            $table->string('valor', 350)->nullable();
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
        Schema::dropIfExists('logs');
    }
}
