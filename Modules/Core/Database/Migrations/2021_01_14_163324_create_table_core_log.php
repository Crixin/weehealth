<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCoreLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_log', function (Blueprint $table) {
            $table->increments('id');
            $table->text('usuario');
            $table->text('tabela');
            $table->text('coluna');
            $table->text('chave');
            $table->text('operacao');
            $table->text('valor_velho')->nullable(true);
            $table->text('valor_novo');
            $table->text('obs')->nullable(true);
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
        Schema::dropIfExists('core_log');
    }
}
