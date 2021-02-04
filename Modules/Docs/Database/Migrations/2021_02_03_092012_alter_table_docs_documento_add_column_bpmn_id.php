<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsDocumentoAddColumnBpmnId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_documento', function (Blueprint $table) {
            $table->foreign('bpmn_id')->references('id')->on('docs_bpmn');
            $table->integer('bpmn_id')->unsigned()->nullable();
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
            $table->dropForeign('docs_documento_bpmn_id_foreign');
            $table->dropColumn('bpmn_id');
        });
    }
}
