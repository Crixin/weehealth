<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PadraoDocs extends Mailable
{
    use Queueable, SerializesModels;

    protected $server;
    protected $etapa;
    protected $documentoId;
    protected $notificacaoPreferencial;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($etapa, int $documentoId, $notificacaoPreferencial = '')
    {
        $this->server = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
        $this->etapa = $etapa;
        $this->documentoId = $documentoId;
        $this->notificacaoPreferencial = $notificacaoPreferencial;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $tagDocumento = new TagDocumentos($this->etapa, $this->documentoId, $this->notificacaoPreferencial);
        $retorno = $tagDocumento->substituirTags();
        return $this->from('portal_conferencia@weecode.com.br', 'Weecode')
        ->subject($retorno['titulo'])
        ->view('emails.padrao')
        ->with([
            'corpo' => $retorno['corpo'],
            'link'  => $retorno['link']
        ]);
    }
}
