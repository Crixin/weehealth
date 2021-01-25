<?php

namespace App\Mail;

use App\Classes\Constants;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Repositories\NotificacaoRepository;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;
use Modules\Docs\Repositories\WorkflowRepository;

class PadraoDocs extends Mailable
{
    use Queueable, SerializesModels;

    protected $server;
    protected $tipoNotificacao;
    protected $documentoId;
    protected $aprovadores;

    protected $modeloNotificacaoRepository;
    protected $documentoRepository;
    protected $workflowRepository;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $tipoNotificacao, int $documentoId)
    {
        $this->server = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];
        $this->modeloNotificacaoRepository = new NotificacaoRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->aprovadores = new UserEtapaDocumentoRepository();
        $this->workflowRepository = new WorkflowRepository();

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
        $buscaDocumento = $this->documentoRepository->find($this->documentoId);
        $buscaTipoNotificacao = $this->modeloNotificacaoRepository->find($this->tipoNotificacao);

        //INICIO APROVADORES
        $buscaAprovadores = $this->aprovadores->findBy(
            [
                ['documento_id', '=', $this->documentoId]
            ]
        );
        $arrayAprovadores = [];
        foreach ($buscaAprovadores as $key => $value) {
            $arrayAprovadores[$value->docsEtapa->nome][$key] = $value->coreUsers->name;
        }

        $montaAprovadores = '';
        foreach ($arrayAprovadores as $keyEtapa => $valueEtapa) {
            $montaAprovadores .= "Etapa - " . $keyEtapa . ", o(s) aprovador(es) são :";
            foreach ($valueEtapa as $key => $valueUsuario) {
                $nome = count($valueEtapa) - 1 != $key ? $valueUsuario . ", " : $valueUsuario;
                $montaAprovadores .= $nome;
            }
            $montaAprovadores .= ' <br> ';
        }
        // FIM APROVADORES

        //INICIO BUSCA ETAPA DIVULGACAO
        $buscaWorkflow = $this->workflowRepository->findBy(
            [
                ['documento_id', '=', $this->documentoId],
                ['comportamento_divulgacao', '=', true, 'HAS', 'docsEtapaFluxo']
            ],
            ['docsEtapaFluxo'],
            [
                ['created_at', 'DESC']
            ]
        );
        //FIM BUSCA ETAPA DIVULGACAO

        $titulo = $buscaTipoNotificacao->titulo_email;
        $corpo  = $buscaTipoNotificacao->corpo_email;

        $tags = Constants::$TAGS_NOTIFICACOES;

        $arrayTags = [];
        foreach ($tags as $key => $value) {
            switch ($value) {
                case '<DATA_ELABORACAO>':
                    $arrayTags['<DATA_ELABORACAO>'] = date('d/m/Y', strtotime($buscaDocumento->created_at));
                    break;
                case '<ELABORADOR>':
                    $arrayTags['<ELABORADOR>'] = $buscaDocumento->coreElaborador->name;
                    break;
                case '<APROVADOR>':
                    $arrayTags['<APROVADOR>'] = $montaAprovadores;
                    break;
                case '<DATA_REVISAO>':
                    $arrayTags['<DATA_REVISAO>'] = $buscaWorkflow->count() > 0 ? date('d/m/Y', strtotime($buscaWorkflow[0]->created_at)) : 'Documento não divulgado';
                    break;
                case '<VERSAO>':
                    $arrayTags['<VERSAO>'] = $buscaDocumento->revisao;
                    break;
                case '<CODIGO_DOCUMENTO>':
                    $arrayTags['<CODIGO_DOCUMENTO>'] = $buscaDocumento->codigo;
                    break;
                case '<TITULO_DOCUMENTO>':
                    $arrayTags['<TITULO_DOCUMENTO>'] = $buscaDocumento->nome;
                    break;
                case '<TIPO_DOCUMENTO>':
                    $arrayTags['<TIPO_DOCUMENTO>'] = $buscaDocumento->docsTipoDocumento->nome;
                    break;
                case '<SETOR>':
                    $arrayTags['<SETOR>'] = $buscaDocumento->coreSetor->nome;
                    break;
            }
        }

        foreach ($arrayTags as $key => $value) {
            $titulo = str_replace($key, $value, $titulo);
            $corpo  = str_replace($key, $value, $corpo);
        }

        return $this->from('portal_conferencia@weecode.com.br', 'Weecode')
        ->subject($titulo)
        ->view('emails.padrao')
        ->with([
            'corpo' => $corpo
        ]);
    }
}
