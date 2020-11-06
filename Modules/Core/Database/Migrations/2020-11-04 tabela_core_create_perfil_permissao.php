<?php

namespace Modules\Core\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePerfilPermissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_perfil_permissao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('perfil_id');
            $table->foreign('perfil_id')->references('id')->on('core_perfil')->onDelete('cascade');
            $table->integer('permissao_id');
            $table->foreign('permissao_id')->references('id')->on('core_permissao')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_perfil_permissao');
    }
}
