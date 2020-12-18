<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsControleRegistro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_controle_registros', function (Blueprint $table) {
            $table->increments('id');
            $table->text('codigo');
            $table->text('titulo');
            $table->integer('nivel_acesso_id');
            $table->boolean('avulso');
            $table->integer('documento_id')->nullable();
            $table->foreign('documento_id')->references('id')->on('docs_documento')->onDelete('cascade');
            $table->integer('setor_id')->unsigned();
            $table->foreign('setor_id')->references('id')->on('core_setor');
            $table->integer('local_armazenamento_id')->nullable(false);
            $table->foreign('local_armazenamento_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->integer('disposicao_id')->nullable(false);
            $table->foreign('disposicao_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->integer('meio_distribuicao_id')->nullable(false);
            $table->foreign('meio_distribuicao_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->integer('protecao_id')->nullable(false);
            $table->foreign('protecao_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->integer('recuperacao_id')->nullable(false);
            $table->foreign('recuperacao_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->integer('tempo_retencao_deposito_id')->nullable(false);
            $table->foreign('tempo_retencao_deposito_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->integer('tempo_retencao_local_id')->nullable(false);
            $table->foreign('tempo_retencao_local_id')->references('id')->on('docs_opcoes_controle_registros');
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('docs_controle_registros');
    }
}
