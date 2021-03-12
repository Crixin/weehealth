<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Model\Setor;
use Modules\Core\Repositories\SetorRepository;

class SetorService
{

    private $rules;
    private $setorRepository;

    public function __construct()
    {
        $setor = new Setor();
        $this->rules = $setor->rules;
        $this->setorRepository = new SetorRepository();
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
                $perfil = $this->setorRepository->create($data);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }


    public function update(array $data)
    {
        try {
            $id = $data['id'];
            $this->rules['nome'] .= "," . $id;
            unset($data['id']);
            
            $validacao = new ValidacaoService($this->rules, $data);
            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data, $id) {
                $perfil = $this->setorRepository->update($data, $id);
            });


            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            return ["success" => false];
        }
    }

    public function delete($delete, $column = "")
    {
        try {
            DB::beginTransaction();
            //FORCE TO CHECK FOREING KEY
            DB::beginTransaction();
            $this->setorRepository->forceDelete($delete, "id");
            DB::rollBack();

            $this->setorRepository->delete($delete, "id");
            DB::commit();

            Helper::setNotify(__("messages.setor.deleteSucess"), 'success|check-circle');
            return ["success" => true, "redirect" => redirect()->back()->withInput()];
        } catch (\Throwable $th) {
            DB::rollBack();
            Helper::setNotify(__("messages.setor.deleteFail"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput(), "message" => __("messages.setor.deleteFail")];
        }
    }
}
