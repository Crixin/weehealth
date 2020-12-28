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

    public function __construct(Perfil $perfil, PerfilRepository $perfilRepository)
    {
        $this->rules = $perfil->rules;
        $this->perfilRepository = $perfilRepository;
    }


    public function store(array $data)
    {
        try {
            $validacao = new ValidacaoService($this->rules, $data);
            
            $errors = $validacao->make();
            
            if ($errors) {
                return redirect()->back()->withErrors($errors)->withInput();
            }

            DB::transaction(function () use ($data) {
                $perfil = $this->perfilRepository->create($data);
            });

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function update(array $data)
    {
        try {
            $this->rules['nome'] .= "," . $data['id'];

            $validacao = new ValidacaoService($this->rules, $data);

            $errors = $validacao->make();

            if ($errors) {
                return redirect()->back()->withErrors($errors)->withInput();
            }

            DB::transaction(function () use ($data) {
                $perfil = $this->perfilRepository->update($data, $data['id']);
            });

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
