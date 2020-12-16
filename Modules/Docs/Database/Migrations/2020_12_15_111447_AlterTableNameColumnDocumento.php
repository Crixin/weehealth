<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNameColumnDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docs_documento', function (Blueprint $table) {
            $table->rename('nivel_acesso, nivel_acesso_id');
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
            $table->rename('nivel_acesso_id, nivel_acesso');
        });
    }
}
