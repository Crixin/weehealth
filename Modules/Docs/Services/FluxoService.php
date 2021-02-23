<?php

namespace Modules\Docs\Services;

use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\EtapaFluxoRepository;
use Modules\Docs\Repositories\FluxoRepository;

class FluxoService
{
    protected $fluxoRepository;
    protected $etapaFluxoRepository;
    protected $etapaFluxoService;

    public function __construct()
    {
        $this->fluxoRepository = new FluxoRepository();
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
    }

    public function store(array $data)
    {
        $createFluxo = $data;
        unset(
            $createFluxo['etapas'],
        );

        DB::beginTransaction();
        try {
            $fluxo = $this->fluxoRepository->create($createFluxo);
            //Cria etapas
            $novaOrdem = 0;
            $etapasComRejeicao = [];
            $etapaFluxoService = new EtapaFluxoService();
            foreach ($data['etapas'] as $key => $value) {
                $novaOrdem += 1 ;
                $etapas = json_decode($value);

                if (!empty($etapas->etapaRejeicao)) {
                    array_push($etapasComRejeicao, $etapas);
                }

                $requestCreate = $this->montaRequest($etapas, $fluxo, $novaOrdem);
                unset($requestCreate['etapa_rejeicao_id']);
                $etapaFluxoService->store($requestCreate);
            }

            //percorre novamente o request salvar etapas de rejeicao
            foreach ($etapasComRejeicao as $key => $etapa) {
                $buscaEtapasRejeicaoCadastradas = $this->etapaFluxoRepository->findOneBy(
                    [
                        ['ordem', '=', $etapa->etapaRejeicao],
                        ['versao_fluxo', '=', $fluxo->versao],
                        ['fluxo_id', "=", $fluxo->id],
                    ]
                );

                $buscaEtapasCadastradas = $this->etapaFluxoRepository->findOneBy(
                    [
                        ['nome', '=', $etapa->nome],
                        ['descricao', '=', $etapa->descricao],
                        ['versao_fluxo', '=', $fluxo->versao],
                        ['fluxo_id', "=", $fluxo->id]
                    ]
                );
                $updateEtapa = $etapaFluxoService->update(
                    ["etapa_rejeicao_id" => $buscaEtapasRejeicaoCadastradas->id],
                    $buscaEtapasCadastradas->id
                );
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }

    public function update(array $data, $id)
    {
        $createFluxo = $data;
        unset(
            $createFluxo['etapas'],
            $createFluxo['nova_versao']
        );
        DB::beginTransaction();
        try {
            $this->fluxoRepository->update($createFluxo, $id);
            $buscaFluxo = $this->fluxoRepository->find($id);

            /**Etapas */
            $etapasRequest = [];
            foreach ($data['etapas'] as $key => $value) {
                $etapas = json_decode($value);

                if ($etapas->id != '') {
                    array_push($etapasRequest, $etapas->id);
                }
            }
            $etapaDelete = $this->etapaFluxoRepository->findBy(
                [
                    ['fluxo_id', "=", $id],
                    ['versao_fluxo', '=', $buscaFluxo->versao],
                    ['id', "", $etapasRequest ?? [] , "NOTIN"]
                ]
            )->pluck('id')->toArray();
            if ($etapaDelete) {
                if (!$this->etapaFluxoRepository->delete($etapaDelete, 'id')) {
                    throw new \Exception('Falha da deleção dos registros');
                }
            }

            //Cria etapas
            $etapasComRejeicaoUpdate = [];
            foreach ($data['etapas'] as $key => $etapa) {
                $etapaAux = json_decode($etapa);
                $novaOrdem = $etapaAux->ordem ;
                $requestUpdate = $this->montaRequest($etapaAux, $buscaFluxo, $novaOrdem);
                if (!empty($etapaAux->etapaRejeicao)) {
                    array_push($etapasComRejeicaoUpdate, $etapaAux);
                }

                if ($data['nova_versao'] || $etapaAux->id == '') {
                    unset($requestUpdate['etapa_rejeicao_id']);
                    $this->etapaFluxoRepository->firstOrCreate($requestUpdate);
                } else {
                    $this->etapaFluxoRepository->update($requestUpdate, $etapaAux->id);
                }
            }

            $this->updateEtapaRejeicao($etapasComRejeicaoUpdate, $buscaFluxo);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }

    public function montaRequest($etapas, $fluxo, $novaOrdem)
    {
        return [
            "fluxo_id"       => $fluxo->id,
            "versao_fluxo"   => $fluxo->versao,
            "ordem"          => $novaOrdem,
            "nome"           => str_replace('&nbsp;', ' ', $etapas->nome),
            "descricao"      => str_replace('&nbsp;', ' ', $etapas->descricao),
            "status_id"      => (int)$etapas->status,
            "perfil_id"      => (int)$etapas->perfil,
            "permitir_anexo" => $etapas->permitirAnexo == 1 ? true : false,
            "obrigatorio"    => $etapas->obrigatoria  == 1 ? true : false,
            "enviar_notificacao" => $etapas->enviarNotificacao  == 1 ? true : false,
            "notificacao_id" => empty($etapas->notificacao) ? null : (int) $etapas->notificacao,
            "comportamento_criacao" => $etapas->comportamentoCriacao  == 1 ? true : false,
            "comportamento_edicao" => $etapas->comportamentoEdicao  == 1 ? true : false,
            "comportamento_aprovacao" => $etapas->comportamentoAprovacao  == 1 ? true : false,
            "comportamento_visualizacao" => $etapas->comportamentoVizualizacao  == 1 ? true : false,
            "comportamento_treinamento" => $etapas->comportamentoTreinamento  == 1 ? true : false,
            "comportamento_divulgacao" => $etapas->comportamentoDivulgacao  == 1 ? true : false,
            "tipo_aprovacao_id" => empty($etapas->tipoAprovacao) ? null : (int) $etapas->tipoAprovacao,
            "etapa_rejeicao_id" => empty($etapas->etapaRejeicao) ? null : (int) $etapas->etapaRejeicao,
            "exigir_lista_presenca" => $etapas->listaPresenca  == 1 ? true : false
        ];
    }

    private function updateEtapaRejeicao($etapasComRejeicaoUpdate, $fluxo)
    {

        foreach ($etapasComRejeicaoUpdate as $key => $etapaComRejeicao) {

            $buscaEtapaUpdate = $this->etapaFluxoRepository->findOneBy(
                [
                    ['ordem', '=' , $etapaComRejeicao->ordem],
                    ['versao_fluxo', '=', $fluxo->versao],
                    ['fluxo_id', '=', $fluxo->id]
                ]
            );
            $buscaOrdemEtapaRejeicao = $this->etapaFluxoRepository->findOneBy(
                [
                    ['id', '=' , $etapaComRejeicao->etapaRejeicao]
                ]
            );
            $buscaEtapaRejeicao = $this->etapaFluxoRepository->findOneBy(
                [
                    ['ordem', '=' , $buscaOrdemEtapaRejeicao->ordem],
                    ['versao_fluxo', '=', $fluxo->versao],
                    ['fluxo_id', '=', $fluxo->id]
                ]
            );
            $update = $this->etapaFluxoRepository->update(['etapa_rejeicao_id' => $buscaEtapaRejeicao->id ], $buscaEtapaUpdate->id);
        }
    }
}
