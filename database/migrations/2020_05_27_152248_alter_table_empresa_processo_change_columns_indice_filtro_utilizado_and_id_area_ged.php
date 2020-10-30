<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEmpresaProcessoChangeColumnsIndiceFiltroUtilizadoAndIdAreaGed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa_processo', function (Blueprint $table) {
            $table->text('indice_filtro_utilizado')->change();
            $table->text('id_area_ged')->change();
            $table->dropUnique('empresa_processo_id_area_ged_unique');
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
            $table->string('id_area_ged', 100)->unique()->change();
            $table->integer('indice_filtro_utilizado')->change();
        });
    }
}
