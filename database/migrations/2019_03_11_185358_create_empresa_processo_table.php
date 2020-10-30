<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaProcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_processo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('indice_filtro_utilizado');
            $table->string('id_area_ged', 100)->unique();
            $table->integer('empresa_id')->unsigned();
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->integer('processo_id')->unsigned();
            $table->foreign('processo_id')->references('id')->on('processo')->onDelete('cascade');
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
        Schema::dropIfExists('empresa_processo');
    }
}
