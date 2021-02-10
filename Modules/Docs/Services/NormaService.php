<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\Norma;
use Modules\Docs\Repositories\CheckListItemNormaRepository;
use Modules\Docs\Repositories\ItemNormaRepository;
use Modules\Docs\Repositories\NormaRepository;

class NormaService
{

    protected $normaRepository;
    protected $itemNormaRepository;
    protected $checkListItemNormaRepository;
    protected $rules;

    public function __construct()
    {
        $norma = new Norma();
        $this->rules = $norma->rules;
        $this->normaRepository = new NormaRepository();
        $this->itemNormaRepository = new ItemNormaRepository();
        $this->checkListItemNormaRepository = new CheckListItemNormaRepository();
    }

    public function store(array $data)
    {
        try {
            if (empty($data['dados'])) {
                Helper::setNotify("Informe algum item da norma.", 'danger|close-circle');
                return ["success" => false, "redirect" => redirect()->back()->withInput()];
            }
            $insert = [
                'descricao'          => $data['descricao'],
                'orgaoRegulador'     => $data['orgao_regulador_id'],
                'cicloAuditoria'     => $data['ciclo_auditoria_id'],
            ];

            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            $itemNorma = $data['dados'];
            unset($data['dados']);
            DB::transaction(function () use ($data, $itemNorma) {
                $norma = $this->normaRepository->create($data);
                $returnItem = $this->createItem($itemNorma, $norma->id);
                if (!$returnItem['success']) {
                    throw new Exception("Error ao criar item norma", 1);
                }
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar a norma. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $montaRequest)
    {
        try {
            if (empty($montaRequest['dados'])) {
                Helper::setNotify("Informe algum item da norma.", 'danger|close-circle');
                return ["success" => false, "redirect" => redirect()->back()->withInput()];
            }
            $this->rules['descricao'] .= "," . $montaRequest['id'];

            $insert = [
                'descricao'          => $montaRequest['descricao'],
                'orgaoRegulador'     => $montaRequest['orgao_regulador_id'],
                'cicloAuditoria'     => $montaRequest['ciclo_auditoria_id'],
            ];

            $validacao = new ValidacaoService($this->rules, $insert);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            $itemNorma = $montaRequest['dados'];
            unset($montaRequest['dados']);
            DB::transaction(function () use ($montaRequest, $itemNorma) {
                $this->normaRepository->update($montaRequest, $montaRequest['id']);
                $returnItem = $this->updateItem($itemNorma, $montaRequest['id']);
                if (!$returnItem['success']) {
                    throw new Exception("Error ao criar item norma", 1);
                }
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao atualizar a norma. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($id)
    {
        $this->normaRepository->delete($id);
    }

    public function createItem($itemNorma, $id)
    {
        try {
            foreach ($itemNorma as $key => $value) {
                $aux = json_decode($value);
                $insertItem = [
                    'numero'    => $aux->numero,
                    'descricao' => $aux->descricao,
                    'norma_id'  => $id
                ];
                $itemNormaService  = new ItemNormaService();
                $itemCriado = $itemNormaService->store($insertItem);
                if (!$itemCriado['success']) {
                    throw new Exception("Erro ao criar item da norma", 1);
                }
                //cria checklist
                if ($itemCriado['data'] && $aux->checklist != '') {
                    $insertChecklist = [
                        'item_norma_id' => $itemCriado['data'],
                        'descricao' => $aux->checklist
                    ];
                    $checkListItemNormaService = new CheckListItemNormaService();
                    $checkList = $checkListItemNormaService->store($insertChecklist);
                    if (!$checkList['success']) {
                        throw new Exception("Erro ao criar o check list do item da norma", 1);
                    }
                }
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o item da norma. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function updateItem($itemNorma, $id)
    {
        try {
            //deleta
            $idItemNorma = [];
            foreach ($itemNorma as $key => $value) {
                $aux = json_decode($value);
                if ($aux->id != '') {
                    array_push($idItemNorma, $aux->id);
                }
            }
            $itemDelete = $this->itemNormaRepository->findBy(
                [
                    ['norma_id', "=", $id],
                    ['id', "", $idItemNorma ?? [] , "NOTIN"]
                ]
            )->pluck('id')->toArray();
            $this->itemNormaRepository->delete($itemDelete, 'id');
            $this->checkListItemNormaRepository->delete($itemDelete, 'item_norma_id');

            //create/update        
            foreach ($itemNorma as $key => $value) {
                $aux = json_decode($value);
                $updateItem = [
                    'numero'    => $aux->numero,
                    'descricao' => $aux->descricao,
                    'norma_id'  => $id
                ];
                $itemNormaService  = new ItemNormaService();
                $itemCriado = $itemNormaService->store($updateItem);
                if (!$itemCriado['success']) {
                    throw new Exception("Erro ao atualizar item da norma", 1);
                }
                //cria checklist
                if ($itemCriado['data'] && $aux->checklist != '') {
                    $insertChecklist = [
                        'item_norma_id' => $itemCriado['data'],
                        'descricao' => $aux->checklist
                    ];
                    $checkListItemNormaService = new CheckListItemNormaService();
                    $checkList = $checkListItemNormaService->store($insertChecklist);
                    if (!$checkList['success']) {
                        throw new Exception("Erro ao criar o check list do item da norma", 1);
                    }
                }
            }

            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o item da norma. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }
}
