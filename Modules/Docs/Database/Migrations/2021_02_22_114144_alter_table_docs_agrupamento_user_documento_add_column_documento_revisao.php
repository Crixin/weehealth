<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsAgrupamentoUserDocumentoAddColumnDocumentoRevisao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_agrupamento_user_documento', function (Blueprint $table) {
            $table->text('documento_revisao')->nullable();
            $table->boolean('documento_lido')->default(false);
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
            $table->dropColumn(['documento_revisao']);
            $table->dropColumn(['documento_lido']);
        });
    }
}
