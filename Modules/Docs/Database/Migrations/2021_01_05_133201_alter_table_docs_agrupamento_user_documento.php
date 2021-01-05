<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsAgrupamentoUserDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_agrupamento_user_documento', function (Blueprint $table) {
            $table->integer('grupo_id')->unsigned()->nullable();
            $table->foreign('grupo_id')->references('id')->on('core_grupo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_agrupamento_user_documento', function (Blueprint $table) {
            $table->dropForeign('docs_agrupamento_user_documento_grupo_id_foreign');
            $table->dropColumn('grupo_id');
        });
    }
}
