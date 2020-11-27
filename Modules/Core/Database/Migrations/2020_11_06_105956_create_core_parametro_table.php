<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreParametroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_parametro', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identificador_parametro', 150);
            $table->text('descricao');
            $table->text('valor_padrao');
            $table->text('valor_usuario')->nullable();
            $table->boolean('ativo');
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
        Schema::dropIfExists('core_parametro');
    }
}
