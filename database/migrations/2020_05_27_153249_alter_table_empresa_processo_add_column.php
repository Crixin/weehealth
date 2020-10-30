<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEmpresaProcessoAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa_processo', function (Blueprint $table) {
            $table->text("todos_filtros_pesquisaveis")->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_processo', function (Blueprint $table) {
            $table->dropColumn('todos_filtros_pesquisaveis');
        });
    }
}
