<?php

namespace Modules\Docs\Services;

use App\Services\ValidacaoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\AgrupamentoUserDocumento;
use Modules\Docs\Repositories\{AgrupamentoUserDocumentoRepository, DocumentoRepository};

class AgrupamentoUserDocumentoService
{
    protected $agrupamentoUserDocumentoRepository;
    protected $documentoRepository;
    protected $rules;


    public function __construct()
    {
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
        $this->documentoRepository = new DocumentoRepository();
        $agrupamentoUserDocumento = new AgrupamentoUserDocumento();
        $this->rules = $agrupamentoUserDocumento->rules;
    }


    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            foreach ($data['grupo_and_user'] ?? [] as $key => $value) {

                $insert = [
                    'grupo_id'        => $value['grupo_id'],
                    'user_id'         => $value['user_id'],
                    'documento_id'    => $data['documento_id'],
                    'tipo'            => $value['tipo'],
                    'documento_revisao' => $data['documento_revisao']
                ];
                /*
                $validacao = new ValidacaoService($this->rules, $insert);
                $errors = $validacao->make();

                if ($errors) {
                    return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
                }
                */

                $this->agrupamentoUserDocumentoRepository->firstOrCreate(
                    [
                        "documento_id" => $data['documento_id'],
                        "user_id"      => $value['user_id'],
                        "grupo_id"     => $value['grupo_id'],
                        'tipo'         => $value['tipo'],
                        'documento_revisao' => $data['documento_revisao']
                    ]
                );
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return ["success" => false];
        }
    }


    public function delete(array $data)
    {
        try {
            DB::beginTransaction();

            $this->agrupamentoUserDocumentoRepository->delete($data, 'id');

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }

    public function iniciarRevisao($data)
    {
        try {
            DB::beginTransaction();

            $documento = $data['documento_id'];

            $documento = $this->documentoRepository->find($documento);
            $agrupamentoUserDocumento = $this->agrupamentoUserDocumentoRepository->findBy(
                [
                    ['documento_id', "=", $documento->id],
                    ['documento_revisao', "=", str_pad($documento->revisao - 1, strlen($documento->revisao), "0", STR_PAD_LEFT)],
                ]
            )->toArray();

            $inserir['grupo_and_user'] = array_map(function ($grupoEndUser) {
                return [
                    'user_id' => $grupoEndUser['user_id'],
                    'tipo' => $grupoEndUser['tipo'],
                    'grupo_id' => $grupoEndUser['grupo_id'],
                ];
            }, $agrupamentoUserDocumento);

            $inserir['documento_id'] = $documento->id;
            $inserir['documento_revisao'] = $documento->revisao;

            if (!$this->store($inserir)) {
                throw new \Exception("Falha ao cadastrar grupo de divulgação e treinamento da nova revisao");
            }
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }

    public function confirmarLeituraGrupoDivulgacao($data)
    {
        try {
            DB::beginTransaction();
            $documento = $data['documento_id'];
            $buscaDocumento = $this->documentoRepository->find($documento);
            $buscaAgrupamentoUserDocumento = $this->agrupamentoUserDocumentoRepository->findBy(
                [
                    ['documento_id', '=', $documento],
                    ['user_id', '=', Auth::user()->id, "AND"],
                    ['tipo', '=', 'DIVULGACAO', "AND"],
                    ['documento_revisao', '=', $buscaDocumento->revisao]
                ]
            );
            unset($data['documento_id']);
            foreach ($buscaAgrupamentoUserDocumento as $key => $agrupamentoUserDocumento) {
                $this->agrupamentoUserDocumentoRepository->update($data, $agrupamentoUserDocumento->id);
            }
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
