<?php

namespace Modules\Docs\Services;

use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\AgrupamentoUserDocumento;
use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;

class AgrupamentoUserDocumentoService
{
    protected $agrupamentoUserDocumentoRepository;
    protected $rules;


    public function __construct()
    {
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
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
                    'tipo'            => $data['tipo']
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
                        'tipo'         => $data['tipo']
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
}
