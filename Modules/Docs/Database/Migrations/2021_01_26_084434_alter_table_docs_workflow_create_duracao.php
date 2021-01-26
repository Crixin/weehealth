<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsWorkflowCreateDuracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_workflow', function (Blueprint $table) {
            $table->text('tempo_duracao_etapa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_workflow', function (Blueprint $table) {
            $table->dropColumn('tempo_duracao_etapa');
        });
    }
}
