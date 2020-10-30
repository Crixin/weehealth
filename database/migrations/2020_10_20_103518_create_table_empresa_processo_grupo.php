<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmpresaProcessoGrupo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_processo_grupo', function (Blueprint $table) {
            $table->id();
            $table->integer('grupo_id')->unsigned();
            $table->foreign('grupo_id')->references('id')->on('grupo')->onDelete('cascade');
            $table->integer('empresa_processo_id')->unsigned();
            $table->foreign('empresa_processo_id')->references('id')->on('empresa_processo')->onDelete('cascade');
            $table->json('filtros');
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
        Schema::dropIfExists('empresa_processo_grupo');
    }
}
