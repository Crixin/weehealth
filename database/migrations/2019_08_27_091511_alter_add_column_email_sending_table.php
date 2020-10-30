<?php

use App\Parametro;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnEmailSendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa_grupo', function (Blueprint $table) {
            $table->boolean('permissao_receber_email')->default(false);
        });


        Schema::table('empresa_user', function (Blueprint $table) {
            $table->boolean('permissao_receber_email')->default(false);
        });


        // Cria o parâmetro que será utilizado para saber se o sistema utiliza envio de e-mails
        $prmEnvioDeEmail = Parametro::create([
            'identificador_parametro' => "PERMITIR_RECEBER_EMAIL",
            'descricao' => "Texto da opção que identifica se o usuário ou grupo está habilitado a receber e-mails sobre movimentações de documentos (rejeições) em determinada empresa.",
            'valor_padrao' => "E-mail?",
            'valor_usuario' => "",
            'ativo' => true,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_grupo', function (Blueprint $table) {
            $table->dropColumn('permissao_receber_email');
        });
        
        
        Schema::table('empresa_user', function (Blueprint $table) {
            $table->dropColumn('permissao_receber_email');
        });
    }
}
