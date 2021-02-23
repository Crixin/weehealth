<?php

namespace Modules\Docs\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Repositories\{EtapaFluxoRepository, DocumentoRepository, UserEtapaDocumentoRepository};

class UserEtapaDocumentoService
{
    protected $userEtapaDocumentoRepository;
    protected $userRepository;
    protected $etapaFluxoRepository;

    public function __construct()
    {
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->userRepository = new UserRepository();
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
        $this->documentoRepository = new DocumentoRepository();
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['grupo_user_etapa'] ?? [] as $grupoUserEtapa) {
                $response = $this->userEtapaDocumentoRepository->firstOrCreate(
                    [
                        "grupo_id"          => $grupoUserEtapa['grupo_id'],
                        "user_id"           => $grupoUserEtapa['user_id'],
                        "etapa_fluxo_id"    => $grupoUserEtapa['etapa_fluxo_id'],
                        "documento_revisao" => $data['documento_revisao'],
                        'documento_id'      => $data['documento_id']
                    ]
                );
                if ($response->wasRecentlyCreated) {
                    $buscaUsuario = $this->userRepository->find($grupoUserEtapa['user_id']);
                    $etapaFluxo   = $this->etapaFluxoRepository->find($grupoUserEtapa['etapa_fluxo_id']);
                    $descricao = 'O usuário ' . $buscaUsuario->name . ' tornou-se aprovador da etapa ' . $etapaFluxo->nome;
                    $responseHistorico = $this->storeHistorico($data['documento_id'], $descricao, $data['documento_revisao']);
                    if (!$responseHistorico['success']) {
                        throw new Exception("Erro ao gravar histórico", 1);
                    }
                }
            }
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return ["success" => false];
        }
    }


    public function delete(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $key => $userEtapaDocumento) {
                $buscaEtapaDocumento = $this->userEtapaDocumentoRepository->find($userEtapaDocumento);
                $descricao = 'O usuário ' . $buscaEtapaDocumento->coreUsers->name . ' foi retirado de aprovador da etapa ' . $buscaEtapaDocumento->docsEtapa->nome;
                $responseHistorico = $this->storeHistorico($buscaEtapaDocumento->documento_id, $descricao, $buscaEtapaDocumento->documento_revisao);
                if (!$responseHistorico['success']) {
                    throw new Exception("Erro ao gravar histórico", 1);
                }
            }
            $deleteEtapaDocumento = $this->userEtapaDocumentoRepository->delete($data, 'id');
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return ["success" => false];
        }
    }


    public function storeHistorico($documento_id, $descricao, $revisao)
    {
        $historicoDocumentoService = new HistoricoDocumentoService();
        $insert = [
            "descricao"    => $descricao,
            "documento_id" => $documento_id,
            "user_id"      => Auth::user()->id,
            "documento_revisao" => $revisao
        ];
        return $historicoDocumentoService->store($insert);
    }


    public function iniciarRevisao($data)
    {
        try {
            DB::beginTransaction();

            $documento = $data['documento_id'];

            $documento = $this->documentoRepository->find($documento);
            $usersEtapaDocumento = $this->userEtapaDocumentoRepository->findBy(
                [
                    ['documento_id', "=", $documento->id],
                    ['documento_revisao', "=", str_pad($documento->revisao - 1, strlen($documento->revisao), "0", STR_PAD_LEFT)],
                ]
            )->toArray();

            $inserir['grupo_user_etapa'] = array_map(function ($userEtapaDoc) {
                return [
                    'user_id' => $userEtapaDoc['user_id'],
                    'etapa_fluxo_id' => $userEtapaDoc['etapa_fluxo_id'],
                    'grupo_id' => $userEtapaDoc['grupo_id'],
                ];
            }, $usersEtapaDocumento);


            $inserir['documento_id'] = $documento->id;
            $inserir['documento_revisao'] = $documento->revisao;

            if (!$this->store($inserir)) {
                throw new \Exception("Falha ao cadastrar aprovadores da nova revisao");
            }
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return ["success" => false];
        }
    }
}
