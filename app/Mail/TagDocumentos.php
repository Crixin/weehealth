<?php

namespace App\Mail;

use App\Classes\Constants;
use Modules\Core\Repositories\NotificacaoRepository;
use Modules\Docs\Model\EtapaFluxo;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;
use Modules\Docs\Repositories\WorkflowRepository;

class TagDocumentos
{
    protected $server;
    protected $tipoNotificacao;
    protected $documentoId;
    protected $versaoDocumento;
    protected $aprovadores;
    protected $etapa;

    protected $modeloNotificacaoRepository;
    protected $documentoRepository;
    protected $workflowRepository;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($etapa, int $documentoId, $idNotificacaoPreferencial = '')
    {
        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            $this->server = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'] ?? '';
        }
        $this->modeloNotificacaoRepository = new NotificacaoRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->aprovadores = new UserEtapaDocumentoRepository();
        $this->workflowRepository = new WorkflowRepository();

        $this->tipoNotificacao = $idNotificacaoPreferencial != '' ? $idNotificacaoPreferencial : $etapa->notificacao_id;
        $this->documentoId = $documentoId;
        $this->etapa = $etapa;
    }


    public function substituirTags()
    {
        $buscaDocumento = $this->documentoRepository->find($this->documentoId);
        $buscaTipoNotificacao = $this->modeloNotificacaoRepository->find($this->tipoNotificacao);

        $link = env('APP_URL') . 'docs/documento/visualizar/' . $this->documentoId;

        //INICIO APROVADORES
        $montaAprovadores = $this->getAprovadores();
        // FIM APROVADORES

        //INICIO BUSCA ETAPA DIVULGACAO
        $buscaWorkflow = $this->getEtapaDivulgacao();
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
                case '<ETAPA>':
                    $arrayTags['<ETAPA>'] = $this->etapa->nome;
                    break;
            }
        }

        foreach ($arrayTags as $key => $value) {
            $titulo = str_replace($key, $value, $titulo);
            $corpo  = str_replace($key, $value, $corpo);
        }
        return [
            "titulo" => $titulo,
            "corpo"  => $corpo,
            "link"   => $link
        ];
    }

    public function getAprovadores()
    {
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
        return $montaAprovadores;
    }

    public function getEtapaDivulgacao()
    {
        return $this->workflowRepository->findBy(
            [
                ['documento_id', '=', $this->documentoId],
                ['comportamento_divulgacao', '=', true, 'HAS', 'docsEtapaFluxo']
            ],
            ['docsEtapaFluxo'],
            [
                ['created_at', 'DESC']
            ]
        );
    }
}
