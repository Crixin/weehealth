<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('permissao_download');
            $table->boolean('permissao_visualizar');
            $table->boolean('permissao_impressao');
            $table->boolean('permissao_aprovar_doc');
            $table->boolean('permissao_excluir_doc');
            $table->boolean('permissao_upload_doc');
            $table->integer('empresa_id')->unsigned();
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->integer('grupo_id')->unsigned();
            $table->foreign('grupo_id')->references('id')->on('grupo')->onDelete('cascade');
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
        Schema::dropIfExists('empresa_grupo');
    }
}
