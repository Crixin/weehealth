<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsEtapaFluxoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_etapa_fluxo', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nome');
            $table->text('descricao');
            $table->integer('fluxo_id')->unsigned();
            $table->foreign('fluxo_id')->references('id')->on('docs_fluxo')->onDelete('cascade');
            $table->integer('perfil_id')->unsigned();
            $table->foreign('perfil_id')->references('id')->on('core_perfil');
            $table->integer('status_id');
            $table->integer('ordem');
            $table->boolean('enviar_notificacao');
            $table->integer('notificacao_id')->unsigned();
            $table->foreign('notificacao_id')->references('id')->on('docs_notificacao');
            $table->boolean('permitir_anexo');
            $table->boolean('comportamento_criacao');
            $table->boolean('comportamento_edicao');
            $table->boolean('comportamento_aprovacao');
            $table->boolean('comportamento_visualizacao');
            $table->boolean('comportamento_divulgacao');
            $table->boolean('comportamento_treinamento');
            $table->integer('tipo_aprovacao');
            $table->boolean('obrigatorio');
            $table->integer('etapa_rejeicao_id')->unsigned();
            $table->foreign('etapa_rejeicao_id')->references('id')->on('docs_etapa_fluxo');
            $table->boolean('exigir_lista_presenca');
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
        Schema::dropIfExists('docs_etapa_fluxo');
    }
}
