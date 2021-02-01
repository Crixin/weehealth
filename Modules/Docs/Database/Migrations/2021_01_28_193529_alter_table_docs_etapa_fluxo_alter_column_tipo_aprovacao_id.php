<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsEtapaFluxoAlterColumnTipoAprovacaoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_etapa_fluxo', function (Blueprint $table) {
            $table->text('tipo_aprovacao_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_etapa_fluxo', function (Blueprint $table) {
            $table->dropColumn('tipo_aprovacao_id')->change();
        });
    }
}
