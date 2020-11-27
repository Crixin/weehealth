<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsTipoDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_tipo_documento', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 80);
            $table->text('descricao');
            $table->string('sigla', 10);
            $table->integer('docs_fluxo_id')->unsigned();
            $table->foreign('docs_fluxo_id')->references('id')->on('docs_fluxo');
            $table->integer('docs_tipo_documento_pai_id')->unsigned();
            $table->foreign('docs_tipo_documento_pai_id')->references('id')->on('docs_tipo_documento');
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
        Schema::dropIfExists('docs_tipo_documento');
    }
}
