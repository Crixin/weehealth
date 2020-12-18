<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsListaPresenca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_lista_presenca', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nome');
            $table->text('ged_documento_id');
            $table->date('data');
            $table->text('descricao');
            $table->text('destinatarios_email')->nullable();
            $table->text('revisao_documento');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento')->onDelete('cascade');
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
        Schema::dropIfExists('docs_lista_presenca');
    }
}
