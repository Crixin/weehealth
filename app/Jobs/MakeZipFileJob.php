<?php

namespace App\Jobs;

use Helper;
use ZipArchive;
use App\Parametro;
use App\Classes\GEDServices;
use App\Classes\FTPServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MakeZipFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ged;
    protected $listaIdsArquivos;
    protected $nomeArquivoZIP;
    protected $empresaPastaFTP;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($_arrIdsArquivos, $_zipName, $_pastaFTP)
    {
        /**
        * OBS: Binary data, such as raw image contents, should be passed through the  base64_encode function before being passed to a queued job. Otherwise, the job 
        * may not properly serialize to JSON when being placed on the queue.  (https://laravel.com/docs/5.8/queues#class-structure)
        */

        $this->ged = new GEDServices(['id_user' => env('ID_GED_USER'), 'server' => env('URL_GED_WSDL')]);
        $this->listaIdsArquivos = $_arrIdsArquivos;
        $this->nomeArquivoZIP = $_zipName;
        $this->empresaPastaFTP = $_pastaFTP;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (!empty($this->listaIdsArquivos['folhas_ponto'])) {
            Storage::makeDirectory('tmp/folhas_ponto', 0777, true, true);
            foreach ($this->listaIdsArquivos['folhas_ponto'] as $nome => $id) {
                $documentoCompleto = $this->ged->pesquisaDocumento($id)->return;
                file_put_contents(storage_path() . '/app/tmp/folhas_ponto/' . $nome, $documentoCompleto->bytes);
            }
        }
        
        if (!empty($this->listaIdsArquivos['documentos'])) {
            Storage::makeDirectory('tmp/documentos', 0777, true, true);
            foreach ($this->listaIdsArquivos['documentos'] as $nome => $id) {
                $documentoCompleto = $this->ged->pesquisaDocumento($id)->return;
                file_put_contents(storage_path() . '/app/tmp/documentos/' . $nome, $documentoCompleto->bytes);
            }
        }

        
        // Define o diretório em que os arquivos estão e cria o arquivo .zip
        $storage_dir = storage_path('app/tmp');
        $this->zip($storage_dir, storage_path('app/tmp/') . $this->nomeArquivoZIP);

        // Envia o arquivo para o FTP, na pasta da empresa selecionada
        $ip =   $this->getValue('FTP_IP');
        $user = $this->getValue('FTP_USUARIO');
        $senha = $this->getValue('FTP_SENHA');
        $caminho_base = $this->getValue('FTP_CAMINHO_BASE');

        $ftp = new FTPServices($ip, $user, $senha);
        $ftpConect = $ftp->connect();
        ftp_put(
            $ftpConect,
            storage_path('app/tmp/') . $this->nomeArquivoZIP,
            $caminho_base . $this->empresaPastaFTP . $this->nomeArquivoZIP
        );
        ftp_close($ftpConect);
        // Depois de enviar para o FTP
        Storage::deleteDirectory('tmp');
    }


    

    /**
     * Snippet from: https://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
     */
    private function zip($source, $destination)
    {
        if (!extension_loaded('zip') || !file_exists($source))
            return false;
    
        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }
        $source = str_replace('\\', '/', realpath($source));
    
        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);
    
            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);
    
                // Ignore "." and ".." folders
                if (in_array( substr( $file, strrpos($file, '/') + 1 ), array('.', '..'))) {
                    continue;
                }
                $file = realpath($file);
    
                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } elseif (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } elseif (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
    
        return $zip->close();
    }

    private function getValue($value)
    {
        $entity = Parametro::where('identificador_parametro', $value)->first();
        return ( !empty($entity->valor_usuario) ) ? $entity->valor_usuario : $entity->valor_padrao;
    }
}
