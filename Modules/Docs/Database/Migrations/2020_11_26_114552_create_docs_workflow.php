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
            $table->integer('etapa_num')->unsigned();
            $table->string('etapa', 50);
            $table->string('descricao', 100);
            $table->text('justificativa');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento')->onDelete();
            $table->integer('etapa_fluxo_id')->unsigned();
            $table->foreign('etapa_fluxo_id')->references('id')->on('docs_etapa_fluxo');
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
