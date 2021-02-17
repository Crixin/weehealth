<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterandoColunasTabelaDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_documento', function (Blueprint $table) {
            $table->renameColumn('ged_documento_id', 'ged_registro_id');
            $table->dropColumn(['justificativa_rejeicao_etapa', 'justificativa_cancelar_etapa']);

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
            $table->renameColumn('ged_registro_id', 'ged_documento_id');
            $table->text('justificativa_rejeicao_etapa')->nullable();
            $table->text('justificativa_cancelar_etapa')->nullable();
        });
    }
}
