<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsFluxoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_fluxo', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nome');
            $table->text('descricao');
            $table->text('versao_fluxo');
            $table->integer('grupo_id')->unsigned();
            $table->foreign('grupo_id')->references('id')->on('core_grupo');
            $table->integer('perfil_id')->unsigned();
            $table->foreign('perfil_id')->references('id')->on('core_perfil');
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
        Schema::dropIfExists('docs_fluxo');
    }
}
