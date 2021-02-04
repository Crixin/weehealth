<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\BpmnRepository;

class BpmnService
{
    protected $bpmnRepository;

    public function __construct()
    {
        $this->bpmnRepository = new BpmnRepository();
    }

    public function create(array $dados)
    {
        DB::beginTransaction();
        try {
            $this->bpmnRepository->create($dados);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }

    public function update(array $dados, int $id)
    {
        DB::beginTransaction();
        try {
            $this->bpmnRepository->update($dados, $id);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }

    public function delete($delete)
    {
        DB::beginTransaction();
        try {
            $this->bpmnRepository->delete($delete);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
