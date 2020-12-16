<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddNumeroPadraoTipoDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_tipo_documento', function (Blueprint $table) {
            $table->integer('numero_padrao');
            $table->integer('ultimo_documento')->default(0);
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
            $table->dropColumn('numero_padrao');
            $table->dropColumn('ultimo_documento');
        });
    }
}
