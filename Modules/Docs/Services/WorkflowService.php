<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\WorkflowRepository;

class WorkflowService
{
    protected $workflowRepository;


    public function __construct(WorkflowRepository $workflowRepository)
    {
        $this->workflowRepository = $workflowRepository;
    }

    public function create(array $dados)
    {
        return $this->workflowRepository->create($dados);
    }

    public function update(array $data, int $id)
    {
        return $this->workflowRepository->update($data, $id);
    }

    public function delete(array $delete)
    {
        return $this->workflowRepository->delete($delete);
    }
}
