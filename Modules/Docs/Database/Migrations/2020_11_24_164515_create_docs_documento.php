<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_documento', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nome')->nullable();
            $table->text('codigo');
            $table->integer('tipo_documento_id')->unsigned();
            $table->foreign('tipo_documento_id')->references('id')->on('docs_tipo_documento');
            $table->date('validade')->nullable();
            $table->boolean('copia_controlada');
            $table->integer('nivel_acesso_id')->nullable();
            $table->integer('elaborador_id')->unsigned();
            $table->foreign('elaborador_id')->references('id')->on('core_users');
            $table->text('revisao')->nullable();
            $table->text('justificativa_rejeicao_etapa')->nullable();
            $table->text('justificativa_cancelar_etapa')->nullable();
            $table->boolean('obsoleto')->default(false);
            $table->integer('classificacao_id')->nullable();
            $table->text('ged_documento_id')->nullable();
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
        Schema::dropIfExists('docs_documento');
    }
}
