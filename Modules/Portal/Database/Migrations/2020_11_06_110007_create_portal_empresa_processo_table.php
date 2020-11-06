<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortalEmpresaProcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portal_empresa_processo', function (Blueprint $table) {
            $table->increments('id');
            $table->text('indice_filtro_utilizado');
            $table->text('id_area_ged');
            $table->integer('empresa_id')->unsigned();
            $table->foreign('empresa_id')->references('id')->on('core_empresa')->onDelete('cascade');
            $table->integer('processo_id')->unsigned();
            $table->foreign('processo_id')->references('id')->on('portal_processo')->onDelete('cascade');
            $table->text("todos_filtros_pesquisaveis")->default("");
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
        Schema::dropIfExists('portal_empresa_processo');
    }
}
