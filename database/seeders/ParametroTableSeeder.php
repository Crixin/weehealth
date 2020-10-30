<?php

namespace Database\Seeders;

use App\Parametro;
use Illuminate\Database\Seeder;

class ParametroTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /**
         * Ações disponíveis no que se refere aos documentos
         */

        $prmDownload = new Parametro();
        $prmDownload->identificador_parametro = "PERMITIR_DOWNLOAD";
        $prmDownload->descricao = "Texto que será exibido na tabela de permissionamento, na opção que permite utilizar o download.";
        $prmDownload->valor_padrao = "Download?";
        $prmDownload->valor_usuario = "";
        $prmDownload->ativo = true;
        $prmDownload->save();
        
        $prmVisualizar = new Parametro();
        $prmVisualizar->identificador_parametro = "PERMITIR_VISUALIZAR";
        $prmVisualizar->descricao = "Texto que será exibido na tabela de permissionamento, na opção que permite visualizar o documento.";
        $prmVisualizar->valor_padrao = "Visualizar?";
        $prmVisualizar->valor_usuario = "";
        $prmVisualizar->ativo = true;
        $prmVisualizar->save();
        
        $prmImprimir = new Parametro();
        $prmImprimir->identificador_parametro = "PERMITIR_IMPRIMIR";
        $prmImprimir->descricao = "Texto que será exibido na tabela de permissionamento, na opção que possibilita realizar impressões do documento.";
        $prmImprimir->valor_padrao = "Imprimir?";
        $prmImprimir->valor_usuario = "";
        $prmImprimir->ativo = true;
        $prmImprimir->save();
        
        $prmAprovar = new Parametro();
        $prmAprovar->identificador_parametro = "PERMITIR_APROVAR";
        $prmAprovar->descricao = "Texto que será exibido na tabela de permissionamento, na opção que habilita a permissão para aprovar um documento.";
        $prmAprovar->valor_padrao = "Aprovar?";
        $prmAprovar->valor_usuario = "";
        $prmAprovar->ativo = true;
        $prmAprovar->save();
        
        $prmExcluir = new Parametro();
        $prmExcluir->identificador_parametro = "PERMITIR_EXCLUIR";
        $prmExcluir->descricao = "Texto que será exibido na tabela de permissionamento, na opção que identifica a permissão de excluir um documento.";
        $prmExcluir->valor_padrao = "Excluir?";
        $prmExcluir->valor_usuario = "";
        $prmExcluir->ativo = true;
        $prmExcluir->save();
        
        $prmUpload = new Parametro();
        $prmUpload->identificador_parametro = "PERMITIR_UPLOAD";
        $prmUpload->descricao = "Texto da opção que identifica a permissão para realizar o upload de um documento externo ao processo em uma área específica.";
        $prmUpload->valor_padrao = "Upload?";
        $prmUpload->valor_usuario = "";
        $prmUpload->ativo = true;
        $prmUpload->save();

        $prmEditar = new Parametro();
        $prmEditar->identificador_parametro = "PERMITIR_EDITAR";
        $prmEditar->descricao = "Texto que será exibido na tabela de permissionamento, na opção que edita um documento.";
        $prmEditar->valor_padrao = "Editar?";
        $prmEditar->valor_usuario = "";
        $prmEditar->ativo = true;
        $prmEditar->save();
        
        
        
        /*
        * Configurações de FTP
        */

        $prmIP = new Parametro();
        $prmIP->identificador_parametro = "FTP_IP";
        $prmIP->descricao = "Endereço IP do servidor de FTP centralizado disponibilizado pelo cliente para que os arquivos comprimidos (.zip) possam ser inseridos.";
        $prmIP->valor_padrao = "192.168.28.2";
        $prmIP->valor_usuario = "";
        $prmIP->ativo = true;
        $prmIP->save();

        $prmPorta = new Parametro();
        $prmPorta->identificador_parametro = "FTP_PORTA";
        $prmPorta->descricao = "Porta de acesso do servidor de FTP definido anteriormente.";
        $prmPorta->valor_padrao = "21";
        $prmPorta->valor_usuario = "";
        $prmPorta->ativo = true;
        $prmPorta->save();

        $prmCaminhoBase = new Parametro();
        $prmCaminhoBase->identificador_parametro = "FTP_CAMINHO_BASE";
        $prmCaminhoBase->descricao = "Caminho para a pasta raiz do FTP. Pode ser útil caso o cliente utilize o FTP para outras demandas e não queira que as pastas criadas pela aplicação fiquem na raiz (/) e sim em uma subpasta (/subpasta). Deve iniciar e acabar com uma barra (/).";
        $prmCaminhoBase->valor_padrao = "/";
        $prmCaminhoBase->valor_usuario = "";
        $prmCaminhoBase->ativo = true;
        $prmCaminhoBase->save();

        $prmCaminhoBase = new Parametro();
        $prmCaminhoBase->identificador_parametro = "FTP_USUARIO";
        $prmCaminhoBase->descricao = "Usuário de acesso ao servidor FTP. Precisa possuir permissões de leitura e escrita.";
        $prmCaminhoBase->valor_padrao = "teste";
        $prmCaminhoBase->valor_usuario = "";
        $prmCaminhoBase->ativo = true;
        $prmCaminhoBase->save();

        $prmCaminhoBase = new Parametro();
        $prmCaminhoBase->identificador_parametro = "FTP_SENHA";
        $prmCaminhoBase->descricao = "Senha de acesso ao servidor FTP.";
        $prmCaminhoBase->valor_padrao = "testelog20sped";
        $prmCaminhoBase->valor_usuario = "";
        $prmCaminhoBase->ativo = true;
        $prmCaminhoBase->save();
    }
}
