<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsRegistroImpressao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_registro_impressoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 100);
            $table->string('obs', 300)->nullable();
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')->references('id')->on('docs_documento');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('core_users');
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
        Schema::dropIfExists('docs_registro_impressoes');
    }
}
