<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddClassificacaoIdDocsDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_documento', function (Blueprint $table) {
            $table->boolean('classificacao_id')->default(false);
            $table->integer('nivel_acesso_id')->change();
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
            $table->dropColumn('classificacao_id');
            $table->string('nivel_acesso', 20)->change();
        });
    }
}
