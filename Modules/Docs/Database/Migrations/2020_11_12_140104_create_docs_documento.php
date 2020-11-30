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
            $table->string('nome', 200);
            $table->string('codigo', 80);
            $table->string('extensao', 10);
            $table->integer('tipo_documento_id')->unsigned();
            $table->foreign('tipo_documento_id')->references('id')->on('docs_tipo_documento');
            $table->date('validade');
            $table->boolean('status');
            $table->text('observacao');
            $table->boolean('copia_controlada');
            $table->string('nivel_acesso', 20);
            $table->boolean('finalizado');
            $table->integer('grupo_treinamento_id')->unsigned();
            $table->foreign('grupo_treinamento_id')->references('id')->on('core_grupo');
            $table->integer('elaborador_id')->unsigned();
            $table->foreign('elaborador_id')->references('id')->on('core_users');
            $table->integer('grupo_divulgacao_id')->unsigned();
            $table->foreign('grupo_divulgacao_id')->references('id')->on('core_grupo');
            $table->boolean('necessita_revisao')->default(false)->nullable();
            $table->integer('id_usuario_solicitante')->nullable();
            $table->string('revisao', 20)->nullable();
            $table->string('justificativa_rejeicao_revisao', 200)->nullable();
            $table->boolean('em_revisao');
            $table->text('justificativa_cancelar_revisao');
            $table->boolean('obsoleto');
            $table->timestamp('data_revisao')->nullable();
            $table->timestamp('validade_anterior')->nullable();
            $table->timestamp('data_revisao_anterior')->nullable();
            $table->text('revisao_curta');
            $table->string('tipo', 50);
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
