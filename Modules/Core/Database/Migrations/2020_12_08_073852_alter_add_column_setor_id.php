<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnSetorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_users', function (Blueprint $table) {
            $table->integer('setor_id')->unsigned()->nullable();
            $table->foreign('setor_id')->references('id')->on('docs_setor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_users', function (Blueprint $table) {
            $table->dropForeign('core_user_setor_id_foreign');
            $table->dropColumn('setor_id');
        });
    }
}
