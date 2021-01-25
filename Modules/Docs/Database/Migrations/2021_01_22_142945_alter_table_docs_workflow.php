<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsWorkflow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_workflow', function (Blueprint $table) {
            $table->text('justificativa')->nullable()->change();
            $table->boolean('justificativa_lida')->nullable()->change();
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
            $table->text('justificativa')->change();
            $table->boolean('justificativa_lida')->change();
        });
    }
}
