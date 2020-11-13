<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_historico', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descricao');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento');
            $table->integer('id_usuario_responsavel')->nullable()->after('descricao');
            $table->string('nome_usuario_responsavel', 80)->nullable()->after('id_usuario_responsavel');
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
        Schema::dropIfExists('docs_historico');
    }
}
