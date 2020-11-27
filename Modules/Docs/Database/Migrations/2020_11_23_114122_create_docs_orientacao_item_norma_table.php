<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsOrientacaoItemNormaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_orientacao_item_norma', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descricao');
            $table->integer('item_norma_id')->unsigned();
            $table->foreign('item_norma_id')->references('id')->on('docs_item_norma');
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
        Schema::dropIfExists('docs_check_list_item_norma');
    }
}
