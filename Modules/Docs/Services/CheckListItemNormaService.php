<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\CheckListItemNorma;
use Modules\Docs\Repositories\CheckListItemNormaRepository;

class CheckListItemNormaService
{

    protected $checkListItemNormaRepository;
    protected $rules;

    public function __construct()
    {
        $this->checkListItemNormaRepository = new CheckListItemNormaRepository();
        $checklistItemNorma = new CheckListItemNorma();
        $this->rules  = $checklistItemNorma->rules;
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
                return $this->checkListItemNormaRepository->firstOrCreate($data);
            });
            return ["success" => true,];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o checklist do item da norma. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }
}
