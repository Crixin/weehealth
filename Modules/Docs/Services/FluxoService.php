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

    public function create(array $data)
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
            foreach ($data['etapas'] as $key => $value) {
                $novaOrdem += 1 ;
                $etapas = json_decode($value);
                $requestCreate = $this->montaRequest($etapas, $fluxo, $novaOrdem);
                $etapaFluxoService = new EtapaFluxoService();
                $etapaFluxoService->create($requestCreate);
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
            foreach ($data['etapas'] as $key => $etapa) {
                $etapaAux = json_decode($etapa);
                $novaOrdem = $etapaAux->ordem ;
                $requestUpdate = $this->montaRequest($etapaAux, $buscaFluxo, $novaOrdem);

                if ($data['nova_versao'] || $etapaAux->id == '') {
                    $this->etapaFluxoRepository->firstOrCreate($requestUpdate);
                } else {
                    $this->etapaFluxoRepository->update($requestUpdate, $etapaAux->id);
                }
            }

            

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
            "nome"           => $etapas->nome,
            "descricao"      => $etapas->descricao,
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
}
