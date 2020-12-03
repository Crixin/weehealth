<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsAgrupamentoUserDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_agrupamento_user_documento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento')->onDelete('cascade');
            $table->integer('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('core_users')->onDelete('cascade');
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
        Schema::dropIfExists('docs_agrupamento_user_documento');
    }
}
