<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsListaPresencaAddColumnExtensao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_lista_presenca', function (Blueprint $table) {
            $table->text('lista_presenca_documento')->nullable();
            $table->text('extensao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_lista_presenca', function (Blueprint $table) {
            $table->dropColumn(['lista_presenca_documento']);
            $table->dropColumn(['extensao']);
        });
    }
}
