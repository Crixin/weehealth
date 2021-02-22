<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\CopiaControlada;
use Modules\Docs\Repositories\CopiaControladaRepository;

class CopiaControladaService
{

    protected $copiaControladaRepository;
    protected $rules;

    public function __construct()
    {
        $this->copiaControladaRepository = new CopiaControladaRepository();
        $copiaControlada = new CopiaControlada();
        $this->rules = $copiaControlada->rules;
    }

    public function create(array $data)
    {
        return $this->copiaControladaRepository->create($data);
    }

    public function store(array $data)
    {
        try {
            $insert = [
                'documento_id'          => $data['documento_id'],
                'user_id'               => $data['user_id'],
                'numero_copias'         => $data['numero_copias'],
                'revisao'               => $data['revisao'],
                'setor'                 => $data['setor']
            ];
            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::transaction(function () use ($insert) {
                $this->copiaControladaRepository->create($insert);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar a copia controlada. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($delete)
    {
        return  $this->copiaControladaRepository->delete($delete);
    }
}
