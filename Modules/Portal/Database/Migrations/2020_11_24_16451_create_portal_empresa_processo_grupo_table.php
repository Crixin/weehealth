<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortalEmpresaProcessoGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portal_empresa_processo_grupo', function (Blueprint $table) {
            $table->id();
            $table->integer('grupo_id')->unsigned();
            $table->foreign('grupo_id')->references('id')->on('core_grupo')->onDelete('cascade');
            $table->integer('empresa_processo_id')->unsigned();
            $table->foreign('empresa_processo_id')->references('id')->on('portal_empresa_processo')->onDelete('cascade');
            $table->json('filtros');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portal_empresa_processo_grupo');
    }
}
