<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\SetorRepository;
use Modules\Docs\Model\TipoDocumento;
use Modules\Docs\Repositories\TipoDocumentoRepository;
use Modules\Docs\Repositories\TipoDocumentoSetorRepository;

class TipoDocumentoService
{
    protected $tipoDocumentoRepository;
    protected $setorRepository;
    protected $tipoDocumentoSetorRepository;
    private $rules;
    private $extensoes;

    public function __construct()
    {
        $tipoDocumento = new TipoDocumento();
        $this->rules = $tipoDocumento->rules;
        $this->tipoDocumentoRepository = new TipoDocumentoRepository();
        $this->setorRepository = new SetorRepository();
        $this->tipoDocumentoSetorRepository = new TipoDocumentoSetorRepository();
        $this->extensoes = str_replace('.', '', implode(", ", json_decode(Helper::buscaParametro('EXTENSAO_DOCUMENTO_ONLYOFFICE'))));
        $this->rules['documentoModelo'] = 'sometimes|mimes:' . str_replace(' ', '', $this->extensoes);
    }

    public function store(array $data)
    {
        try {
            $insert = [
                'nome'                  => $data['nome'],
                'descricao'             => $data['descricao'],
                'sigla'                 => $data['sigla'],
                'fluxo'                 => $data['fluxo_id'],
                'periodoVigencia'       => $data['periodo_vigencia'],
                'periodoAviso'          => $data['periodo_aviso'],
                'documentoModelo'       => $data['mime_type'],
                'codigoPadrao'          => $data['codigo_padrao'],
                'numeroPadrao'          => $data['numero_padrao_id'],
                'ultimoDocumento'       => $data['ultimo_documento'] ?? 0,
            ];
            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            $itemTipoDocumento = $data['dados'];
            unset($data['dados']);
            DB::transaction(function () use ($data, $itemTipoDocumento) {
                $tipoDocumento = $this->tipoDocumentoRepository->create($data);
                if (!$this->createItem($itemTipoDocumento, $tipoDocumento->id)['success']) {
                    throw new Exception("Error ao criar item tipo de documento", 1);
                }
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao cadastrar o tipo de documento. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $data)
    {
        try {
            $buscaTipoDocumento = $this->tipoDocumentoRepository->find($data['id']);
            $this->rules['nome'] .= "," . $data['id'];
            $insert = [
                'nome'                  => $data['nome'],
                'descricao'             => $data['descricao'],
                'sigla'                 => $data['sigla'],
                'fluxo'                 => $data['fluxo_id'],
                'periodoVigencia'       => $data['periodo_vigencia'],
                'periodoAviso'          => $data['periodo_aviso'],
                'documentoModelo'       => $data['mime_type'] ?? '',
                'codigoPadrao'          => $data['codigo_padrao'],
                'numeroPadrao'          => $data['numero_padrao_id'],
                'ultimoDocumento'       => $data['ultimo_documento'],
            ];
            if (empty($data['mime_type'])) {
                unset($this->rules['documentoModelo']);
            }
            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            $itemTipoDocumento = $data['dados'];
            unset($data['dados']);
            DB::transaction(function () use ($data, $itemTipoDocumento) {
                $notificacao = $this->tipoDocumentoRepository->update($data, $data['id']);
                if (!$this->updateItem($itemTipoDocumento, $data['id'])['success']) {
                    throw new Exception("Error ao alterar item tipo de documento", 1);
                }
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao tipo de documento. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function getEtapasFluxosPorComportamento($idTipoDocumento, $comportamento)
    {
        $etapas = [];
        $buscaTipoDocumento = $this->tipoDocumentoRepository->findOneBy(
            [
                ['ativo', '=', true],
                ['id', '=', $idTipoDocumento, "AND"]
            ]
        );
        $tipoDocumento['tipo'] = [
            'id' => $buscaTipoDocumento->id,
            'vinculo_obrigatorio' => $buscaTipoDocumento->vinculo_obrigatorio,
            'vinculo_obrigatorio_outros_doc' => $buscaTipoDocumento->vinculo_obrigatorio_outros_documento,
            'tipo_documento_pai' => $buscaTipoDocumento->tipo_documento_pai_id
        ];
        foreach ($buscaTipoDocumento->docsFluxo->docsEtapaFluxo as $key => $value2) {
            if ($value2->$comportamento == true && $value2->versao_fluxo == $buscaTipoDocumento->docsFluxo->versao) {
                $etapas[] =
                [
                    'id'    => $value2->id,
                    'nome'  => ucfirst($value2->nome),
                    'ordem' => $value2->ordem,
                    'obrigatorio' => $value2->obrigatorio
                ];
            }
        }

        $this->ordenacaoArray($etapas, 'ordem');
        $tipoDocumento['etapas'] = $etapas;
        return $tipoDocumento;
    }

    public function ordenacaoArray(&$array, $column, $direction = SORT_ASC)
    {
        $reference_array = array();
        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }
        array_multisort($reference_array, $direction, $array);
    }

    public function createItem($itemTipoDocumento, $id)
    {
        try {
            foreach ($itemTipoDocumento as $key => $value) {
                $aux = json_decode($value);
                $buscaSetor = $this->setorRepository->findOneBy(
                    [
                        ['nome', '=', $aux->setor]
                    ]
                );
                $insertItem = [
                    'ultimo_documento'  => (int) $aux->numero,
                    'setor_id'          => $buscaSetor->id,
                    'tipo_documento_id' => $id
                ];

                $tipoDocumentoSetorService  = new TipoDocumentoSetorService();
                if (!$tipoDocumentoSetorService->store($insertItem)['success']) {
                    throw new Exception("Erro ao criar item do tipo de documento", 1);
                }
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o item do tipo de documento. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function updateItem($tipoDocumentoSetor, $id)
    {
        try {
            //deleta
            $idTipoDocumentoSetor = [];
            foreach ($tipoDocumentoSetor as $key => $value) {
                $aux = json_decode($value);
                if ($aux->id != '') {
                    array_push($idTipoDocumentoSetor, $aux->id);
                }
            }
            $itemDelete = $this->tipoDocumentoSetorRepository->findBy(
                [
                    ['tipo_documento_id', "=", $id],
                    ['id', "", $idTipoDocumentoSetor ?? [] , "NOTIN"]
                ]
            )->pluck('id')->toArray();
            $this->tipoDocumentoSetorRepository->delete($itemDelete, 'id');

            //create/update        
            foreach ($tipoDocumentoSetor as $key => $value) {
                $aux = json_decode($value);
                $buscaSetor = $this->setorRepository->findOneBy(
                    [
                        ['nome', '=', $aux->setor]
                    ]
                );
                $updateItem = [
                    'ultimo_documento'  => $aux->numero,
                    'setor_id'          => $buscaSetor->id,
                    'tipo_documento_id' => $id
                ];
                $tipoDocumentoSetorService  = new tipoDocumentoSetorService();
                $itemCriado = $tipoDocumentoSetorService->store($updateItem);
                if (!$itemCriado['success']) {
                    throw new Exception("Erro ao atualizar item do tipo de documento", 1);
                }
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao cadastrar o item do tipo de documento. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }
}
