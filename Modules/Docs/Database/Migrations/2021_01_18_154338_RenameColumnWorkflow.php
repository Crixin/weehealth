<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnWorkflow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_workflow', function (Blueprint $table) {
            $table->renameColumn('versao_documento', 'documento_revisao');
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
            $table->renameColumn('documento_revisao', 'versao_documento');
        });
    }
}
