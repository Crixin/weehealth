<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortalDossieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portal_dossie', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->text('caminho_documento');
            $table->text('destinatarios');
            $table->string('status');
            $table->dateTime('validade');
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
        Schema::dropIfExists('portal_dossie');
    }
}
