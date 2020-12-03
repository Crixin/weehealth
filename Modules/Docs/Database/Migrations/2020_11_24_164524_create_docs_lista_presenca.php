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
            $table->string('nome', 80);
            $table->string('extensao', 10);
            $table->date('data');
            $table->text('descricao');
            $table->text('destinatarios_email')->nullable();
            $table->string('revisao_documento', 10)->nullable();
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento')->onDelete();
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
