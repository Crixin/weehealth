<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddVinculoObrigatorioDocsTipoDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_tipo_documento', function (Blueprint $table) {
            $table->boolean('vinculo_obrigatorio_outros_documento')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_tipo_documento', function (Blueprint $table) {
            $table->dropColumn('vinculo_obrigatorio_outros_documento');
        });
    }
}
