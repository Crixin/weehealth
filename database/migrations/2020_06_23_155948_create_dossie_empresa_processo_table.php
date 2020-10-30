<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDossieEmpresaProcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossie_empresa_processo', function (Blueprint $table) {
            $table->id();
            $table->integer('dossie_id');
            $table->foreign('dossie_id')->references('id')->on('dossie')->onDelete('cascade');
            $table->integer('empresa_processo_id');
            $table->foreign('empresa_processo_id')->references('id')->on('empresa_processo')->onDelete('cascade');
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
        Schema::dropIfExists('dossie_processo');
    }
}
