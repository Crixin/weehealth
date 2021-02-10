<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use Modules\Core\Model\Perfil;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\PerfilRepository;

class PerfilService
{

    private $rules;
    private $perfilRepository;

    public function __construct()
    {
        $perfil = new Perfil();
        $this->rules = $perfil->rules;
        $this->perfilRepository = new PerfilRepository();
    }


    public function store(array $data)
    {
        try {
            $validacao = new ValidacaoService($this->rules, $data);
            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $perfil = $this->perfilRepository->create($data);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    public function update(array $data)
    {
        try {
            $this->rules['nome'] .= "," . $data['id'];

            $validacao = new ValidacaoService($this->rules, $data);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $perfil = $this->perfilRepository->update($data, $data['id']);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }
}
