<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDocsTipoDocumentoSetor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_tipo_documento_setor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setor_id')->unsigned();
            $table->foreign('setor_id')->references('id')->on('core_setor')->onDelete('cascade');
            $table->integer('tipo_documento_id')->unsigned();
            $table->foreign('tipo_documento_id')->references('id')->on('docs_tipo_documento');
            $table->integer('ultimo_documento')->default(0);
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
        Schema::dropIfExists('docs_tipo_documento_setor');
    }
}
