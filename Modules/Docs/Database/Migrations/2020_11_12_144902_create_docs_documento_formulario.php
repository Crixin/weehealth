<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsDocumentoFormulario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_documento_formulario', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento');
            $table->integer('formulario_id')->unsigned();
            $table->foreign('formulario_id')->references('id')->on('docs_documento');
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
        Schema::dropIfExists('docs_documento_formulario');
    }
}
