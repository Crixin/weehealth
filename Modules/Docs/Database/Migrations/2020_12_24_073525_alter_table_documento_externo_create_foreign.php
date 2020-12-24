<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocumentoExternoCreateForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_documento_externo', function (Blueprint $table) {
            $table->dropForeign('docs_documento_externo_setor_id_foreign');
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
        Schema::table('docs_documento_externo', function (Blueprint $table) {
            $table->dropForeign('docs_documento_externo_setor_id_foreign');
            $table->foreign('setor_id')->references('id')->on('core_grupo');
        });
    }
}
