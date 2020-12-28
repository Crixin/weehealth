<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocumentoCreateSetorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_documento', function (Blueprint $table) {
            $table->integer('setor_id')->unsigned()->nullable();
            $table->foreign('setor_id')->references('id')->on('core_setor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_documento', function (Blueprint $table) {
            $table->dropForeign('docs_documento_setor_id_foreign');
            $table->dropColumn('setor_id');
        });
    }
}
