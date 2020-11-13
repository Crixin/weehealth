<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsDocumentoExterno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_documento_externo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_documento', 40);
            $table->string('id_registro', 40);
            $table->string('id_area', 40);
            $table->boolean('validado')->default(false);
            // Usuário que fez o upload do documento
            $table->integer('responsavel_upload_id')->unsigned();
            $table->foreign('responsavel_upload_id')->references('id')->on('core_users');
            // Usuário que marcar o 'checkbox' validado
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('core_users');
            $table->integer('setor_id')->unsigned();
            $table->foreign('setor_id')->references('id')->on('docs_setor');
            $table->integer('empresa_id')->nullable(true)->references('id')->on('core_empresa');
            $table->string('revisao')->nullable(true);
            $table->date('validade')->nullable(true);
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
        Schema::dropIfExists('docs_documento_externo');
    }
}
