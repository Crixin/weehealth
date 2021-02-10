<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\ItemNorma;
use Modules\Docs\Repositories\ItemNormaRepository;

class ItemNormaService
{

    protected $itemNormaRepository;
    protected $rules;

    public function __construct()
    {
        $this->itemNormaRepository = new ItemNormaRepository();
        $itemNorma = new ItemNorma();
        $this->rules = $itemNorma->rules;
    }

    public function store(array $data)
    {
        try {
            $validacao = new ValidacaoService($this->rules, $data);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            $item = DB::transaction(function () use ($data) {
                return $this->itemNormaRepository->firstOrCreate($data);
            });
            return ["success" => true, "data" => $item->id];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o item da norma. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

}
