<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsItemNormaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_item_norma', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descricao');
            $table->integer('norma_id')->unsigned();
            $table->foreign('norma_id')->references('id')->on('docs_norma');
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
        Schema::dropIfExists('docs_item_norma');
    }
}
