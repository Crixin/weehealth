<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsSetor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_setor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 80);
            $table->string('sigla', 5);
            $table->text('descricao');
            $table->integer('tipo_setor_id')->unsigned();
            $table->foreign('tipo_setor_id')->references('id')->on('docs_tipo_setor');
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
        Schema::dropIfExists('docs_setor');
    }
}
