<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsGrupoPlanoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_grupo_plano', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grupo_id')->unsigned();
            $table->foreign('grupo_id')->references('id')->on('core_grupo');
            $table->integer('norma_id')->unsigned();
            $table->foreign('norma_id')->references('id')->on('docs_norma');
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
        Schema::dropIfExists('docs_grupo_plano');
    }
}
