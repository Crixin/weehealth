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
            $table->string('nome', 80);
            $table->text('descricao');
            $table->string('sigla', 10);
            $table->integer('fluxo_id')->unsigned();
            $table->foreign('fluxo_id')->references('id')->on('docs_fluxo');
            $table->integer('tipo_documento_pai_id')->unsigned();
            $table->foreign('tipo_documento_pai_id')->references('id')->on('docs_tipo_documento');
            $table->integer('periodo_vigencia_id');
            $table->boolean('ativo');
            $table->boolean('vinculo_obrigatorio');
            $table->boolean('permitir_download');
            $table->boolean('permitir_impressao');
            $table->integer('periodo_aviso_id');
            $table->text('documento_modelo');
            $table->text('codigo_padrao');
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
