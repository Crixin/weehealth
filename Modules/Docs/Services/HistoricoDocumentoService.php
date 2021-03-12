<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\HistoricoDocumento;
use Modules\Docs\Repositories\HistoricoDocumentoRepository;

class HistoricoDocumentoService
{

    protected $historicoDocumentoRepository;
    protected $rules;

    public function __construct()
    {
        $this->historicoDocumentoRepository = new HistoricoDocumentoRepository();
        $historicoDocumento = new HistoricoDocumento();
        $this->rules = $historicoDocumento->rules;
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
                $this->historicoDocumentoRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }
}
