<?php

namespace App\Console\Commands;

use App\Mail\TagDocumentos;
use Illuminate\Console\Command;
use Modules\Core\Repositories\NotificacaoRepository;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Services\NotificacaoService;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Services\WorkflowService;

class ValidadeDocumento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:validadeDocumento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotina que verifica a validade do documento e envia as devidas notificações';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $documentoRepository = new DocumentoRepository();
        $parametroRepository = new ParametroRepository();
        $notificacaoRepository = new NotificacaoRepository();
        $notificacaoService = new NotificacaoService();
        $workflowService = new WorkflowService();

        $notificacaoVencimento = $parametroRepository->getParametro('NOTIFICACAO_DOCUMENTO_VENCIDO');
        $notificacaoValidadeDocumento = $parametroRepository->getParametro('NOTIFICACAO_VALIDADE_DOCUMENTO');

        $dataAtual = date('Y-m-d');
        $documentos = $documentoRepository->findAll();
        foreach ($documentos as $key => $documento) {
            $diasAviso = $documento->docsTipoDocumento->periodo_aviso;
            $etapaAtual = $workflowService->getEtapaAtual($documento->id);

            $usuarios = [$documento->coreElaborador->email];
            if ($documento->validade < $dataAtual && $documento->validade == date('Y-m-d', strtotime('-1 days'))) {
                //Documento Vencido
                $buscaCorpo = new TagDocumentos($etapaAtual, $documento->id, $notificacaoVencimento);
                $tagDocumento = $buscaCorpo->substituirTags();
                $responseNotificacao = $notificacaoService->createNotificacaoSistema($usuarios, $tagDocumento['titulo'], $tagDocumento['corpo'], $tagDocumento['link']);
            } elseif ($documento->validade == date('Y-m-d', strtotime('+' . $diasAviso . ' days'))) {
                //Validade Documento
                $buscaCorpo = new TagDocumentos($etapaAtual, $documento->id, $notificacaoValidadeDocumento);
                $tagDocumento = $buscaCorpo->substituirTags();
                $responseNotificacao = $notificacaoService->createNotificacaoSistema($usuarios, $tagDocumento['titulo'], $tagDocumento['corpo'], $tagDocumento['link']);
            }
        }
        return true;
    }
}
