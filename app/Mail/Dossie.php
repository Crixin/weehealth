<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Repositories\SetupRepository;

class Dossie extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    private $setupRepository;

    
    protected $logoPath;
    protected $iconPath;
    protected $server;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_token, $_server)
    {
        $this->token = $_token;
        $this->server = $_server;
        $this->setupRepository = new SetupRepository();
        $this->logoPath = public_path() . '/images/emails/logo.png';
        $this->iconPath = public_path() . '/images/emails/docs.png';

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $setup = $this->setupRepository->find(1);

        return $this->from('portal_conferencia@weecode.com.br', 'Weecode')
        ->subject('Dossiê de Documentos')
        ->view('emails.dossie')
        ->with([
            'token'           => $this->token,
            'logoPath'        => $this->logoPath,
            'iconPath'        => $this->iconPath,
            'server'          => $this->server,
        ]);
    }
}
