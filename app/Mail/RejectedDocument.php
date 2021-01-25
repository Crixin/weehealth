<?php

namespace App\Mail;

use Modules\Core\Model\{Empresa, User};
use Modules\Portal\Model\{Processo};
use App\Classes\Constants;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectedDocument extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $date;
    protected $logoPath;
    protected $iconPath;
    protected $enterprise;
    protected $process;
    protected $valueLabel;
    protected $researchedValue;
    protected $justify;
    protected $documentName;
    protected $documentType;
    protected $user;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $_enterpriseId, int $_processId, string $_researchedValue, string $_justify, string $_documentName, string $_documentType, int $_userId)
    {
        
        // Confirma que a data será exibida em Português
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $this->date = strftime('%A, %d de %B de %Y', strtotime('today'));

        // Define o caminho das imagens anexadas no corpo do e-mail (estáticas)
        // $this->logoPath = public_path() . '/images/emails/logo_separado.png';
        $this->logoPath = public_path() . '/images/emails/logo_log20.jpg';
        $this->iconPath = public_path() . '/images/emails/reject.png';

        // Configura propriedades da tabela de informações do corpo do e-mail
        $this->enterprise      = Empresa::find($_enterpriseId);
        $this->process         = Processo::find($_processId);
        $this->valueLabel      = $this->checkValueType($_researchedValue);
        $this->researchedValue = $_researchedValue;
        $this->justify         = $_justify;
        $this->documentName    = $_documentName;
        $this->documentType    = $_documentType;
        $this->user            = User::find($_userId);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::debug(Constants::$LOG . "No retorno para a view. (Provavelmente) Os e-mails foram enviados com sucesso! \n\n\n");

        return $this->view('emails.rejected_documents')
                    ->subject("Documento Rejeitado")
                    ->with([
                        'date'            => $this->date,
                        'logoPath'        => $this->logoPath,
                        'iconPath'        => $this->iconPath,
                        'enterpriseName'  => $this->enterprise->nome,
                        'processName'     => $this->process->nome,
                        'valueLabel'      => $this->valueLabel,
                        'researchedValue' => $this->researchedValue,
                        'justify'         => $this->justify,
                        'documentName'    => $this->documentName,
                        'documentType'    => $this->documentType,
                        'responsible'     => $this->user->name,
                    ]);
    }


    private function checkValueType($_value) {
        return preg_match("/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/", $_value) ? 'CPF' : 'MATRÍCULA';
    }
}
