<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsNotificacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_notificacao', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nome');
            $table->integer('tipo_id');
            $table->text('titulo_email');
            $table->text('corpo_email');
            $table->integer('tipo_envio_notificacao_id');
            $table->boolean('documento_anexo');
            $table->softDeletes();
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
        Schema::dropIfExists('docs_notificacao');
    }
}
