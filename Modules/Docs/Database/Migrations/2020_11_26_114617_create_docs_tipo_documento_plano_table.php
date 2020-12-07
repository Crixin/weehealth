<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsTipoDocumentoPlanoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_tipo_documento_plano', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plano_id')->unsigned();
            $table->foreign('plano_id')->references('id')->on('docs_plano')->onDelete('cascade');
            $table->integer('tipo_documento_id')->unsigned();
            $table->foreign('tipo_documento_id')->references('id')->on('docs_tipo_documento');
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
        Schema::dropIfExists('docs_tipo_documento_plano');
    }
}
