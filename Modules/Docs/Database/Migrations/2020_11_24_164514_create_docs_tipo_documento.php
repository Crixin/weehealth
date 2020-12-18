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
            $table->text('nome');
            $table->text('descricao')->nullable();
            $table->text('sigla')->nullable();
            $table->integer('fluxo_id')->unsigned();
            $table->foreign('fluxo_id')->references('id')->on('docs_fluxo');
            $table->integer('tipo_documento_pai_id')->unsigned()->nullable();
            $table->foreign('tipo_documento_pai_id')->references('id')->on('docs_tipo_documento');
            $table->integer('periodo_vigencia')->nullable();
            $table->boolean('ativo');
            $table->boolean('vinculo_obrigatorio');
            $table->boolean('permitir_download');
            $table->boolean('permitir_impressao');
            $table->integer('periodo_aviso')->nullable();
            $table->text('modelo_documento')->nullable();
            $table->text('codigo_padrao');
            $table->boolean('vinculo_obrigatorio_outros_documento')->default(false);
            $table->integer('numero_padrao_id');
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
        Schema::dropIfExists('docs_tipo_documento');
    }
}
