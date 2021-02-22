<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PadraoDocs extends Mailable
{
    use Queueable, SerializesModels;

    protected $server;
    protected $tipoNotificacao;
    protected $documentoId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $tipoNotificacao, int $documentoId)
    {
        $this->server = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
        $this->tipoNotificacao = $tipoNotificacao;
        $this->documentoId = $documentoId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $tagDocumento = new TagDocumentos($this->tipoNotificacao, $this->documentoId);
        $retorno = $tagDocumento->substituirTags();
        return $this->from('portal_conferencia@weecode.com.br', 'Weecode')
        ->subject($retorno['titulo'])
        ->view('emails.padrao')
        ->with([
            'corpo' => $retorno['corpo']
        ]);
    }
}
