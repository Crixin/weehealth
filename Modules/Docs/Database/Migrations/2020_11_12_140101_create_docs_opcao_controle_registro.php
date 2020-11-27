<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsOpcaoControleRegistro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_opcoes_controle_registros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('campo', 100);
            $table->string('descricao', 350);
            $table->boolean('ativo');
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
        Schema::dropIfExists('docs_opcoes_controle_registros');
    }
}
