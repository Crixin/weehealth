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
            $table->text('ged_documento_id');
            $table->text('ged_registro_id');
            $table->text('ged_area_id');
            $table->boolean('validado')->default(false);
            // Usuário que fez o upload do documento
            $table->integer('user_responsavel_upload_id')->unsigned();
            $table->foreign('user_responsavel_upload_id')->references('id')->on('core_users');
            // Usuário que marcar o 'checkbox' validado
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('core_users');
            $table->integer('setor_id')->unsigned();
            $table->foreign('setor_id')->references('id')->on('core_grupo');
            $table->foreignId('empresa_id')->constrained('core_empresa');
            $table->text('revisao')->nullable(true);
            $table->date('validade')->nullable(true);
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
        Schema::dropIfExists('docs_documento_externo');
    }
}
