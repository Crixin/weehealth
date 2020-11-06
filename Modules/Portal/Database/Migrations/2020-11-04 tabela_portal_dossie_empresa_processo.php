<?php

namespace Modules\Portal\Database\Migrations;

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
        Schema::create('portal_dossie_empresa_processo', function (Blueprint $table) {
            $table->id();
            $table->integer('dossie_id');
            $table->foreign('dossie_id')->references('id')->on('portal_dossie')->onDelete('cascade');
            $table->integer('empresa_processo_id');
            $table->foreign('empresa_processo_id')->references('id')->on('portal_empresa_processo')->onDelete('cascade');
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
        Schema::dropIfExists('portal_dossie_empresa_processo');
    }
}
