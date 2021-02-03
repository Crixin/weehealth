<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsUserEtapaDocumentoAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_user_etapa_documento', function (Blueprint $table) {
            $table->text('documento_revisao')->nullable();
            $table->boolean('aprovado')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_user_etapa_documento', function (Blueprint $table) {
            $table->dropColumn(['documento_revisao', 'aprovado']);
        });
    }
}
