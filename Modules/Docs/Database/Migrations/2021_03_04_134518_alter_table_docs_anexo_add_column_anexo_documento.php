<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDocsAnexoAddColumnAnexoDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_anexo', function (Blueprint $table) {
            $table->text('anexo_documento')->nullable();
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
        Schema::table('docs_anexo', function (Blueprint $table) {
            $table->dropColumn(['anexo_documento']);
            $table->dropColumn(['extensao']);
        });
    }
}
