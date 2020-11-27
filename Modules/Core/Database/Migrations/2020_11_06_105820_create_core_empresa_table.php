<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100);
            $table->string('cnpj', 18);
            $table->string('telefone', 15);
            $table->string('responsavel_contato', 50);
            $table->string('pasta_ftp', 150);
            $table->text('obs')->nullable();
            $table->integer('cidade_id')->unsigned();
            $table->foreign('cidade_id')->references('id')->on('core_cidade');
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
        Schema::dropIfExists('core_empresa');
    }
}
