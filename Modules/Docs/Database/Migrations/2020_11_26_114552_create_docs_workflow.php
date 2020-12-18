<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsWorkflow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_workflow', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descricao');
            $table->text('justificativa');
            $table->boolean('justificativa_lida');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento')->onDelete('cascade');
            $table->integer('etapa_fluxo_id')->unsigned();
            $table->foreign('etapa_fluxo_id')->references('id')->on('docs_etapa_fluxo');
            $table->foreignId('user_id')->constrained('core_users');
            $table->text('versao_documento');
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
        Schema::dropIfExists('docs_workflow');
    }
}
