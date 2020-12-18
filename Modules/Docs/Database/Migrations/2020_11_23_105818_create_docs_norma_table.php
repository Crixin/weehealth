<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsNormaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_norma', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descricao');
            $table->integer('orgao_regulador_id')->nullable();
            $table->boolean('ativo');
            $table->integer('ciclo_auditoria_id')->nullable();
            $table->date('data_acreditacao')->nullable();
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
        Schema::dropIfExists('docs_norma');
    }
}
