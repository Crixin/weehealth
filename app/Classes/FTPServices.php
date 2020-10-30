<?php

namespace App\Classes;

use FFI\Exception;
use Illuminate\Support\Facades\Log;

class FTPServices
{
    protected $host;
    protected $user;
    protected $pass;
   
    /*
    * Construtor
    */
    public function __construct($_host, $_user, $_pass)
    {
        $this->host = $_host;
        $this->user = $_user;
        $this->pass = $_pass;
    }

    /*
    * Getters e Setters
    */
    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }


    /*
    * Métodos
    */
    public function connect()
    {
        try {
            Log::info(" \n ### SPEED_LOG ### $this->host | $this->user | $this->pass");

            // Estabelece a conexão básica com o FTP
            $conn_id = ftp_connect($this->host);
            // Realiza o login através de usuário e senha
            $login_result = ftp_login($conn_id, $this->user, $this->pass);

            if ((!$conn_id) || (!$login_result)) {
                error_log("\n A conexão ao FTP falhou! \n");
                Log::info(" \n ### SPEED_LOG ### A conexão falhou!");
                new Exception('A conexão ao FTP falhou!');
            } else {
                ftp_pasv($conn_id, true);
                Log::info(" \n ### SPEED_LOG ### No método para enviar para o FTP e: " . $login_result);
                return $conn_id;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
