<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->boolean('utilizar_permissoes_nivel_usuario')->default(false);
            $table->string('password');
            $table->boolean('administrador')->default(false);
            $table->integer('perfil_id')->nullable();
            $table->foreign('perfil_id')->references('id')->on('core_perfil');
            $table->integer('setor_id')->unsigned()->nullable();
            $table->foreign('setor_id')->references('id')->on('core_setor');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('core_users');
    }
}
