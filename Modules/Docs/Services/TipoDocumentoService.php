<?php

namespace Modules\Docs\Services;

use App\Classes\Constants;
use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\TipoDocumento;
use Modules\Docs\Repositories\TipoDocumentoRepository;

class TipoDocumentoService
{
    protected $tipoDocumentoRepository;
    private $rules;
    private $extensoes;

    public function __construct()
    {
        $tipoDocumento = new TipoDocumento();
        $this->rules = $tipoDocumento->rules;
        $this->tipoDocumentoRepository = new TipoDocumentoRepository();
        $this->extensoes = implode(', ', Constants:: $EXTENSAO_ONLYOFFICE);
        $this->rules['documentoModelo'] = 'sometimes|mimes:' . $this->extensoes;
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
                'ultimoDocumento'       => $data['ultimo_documento'],
            ];

            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $notificacao = $this->tipoDocumentoRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
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
            DB::transaction(function () use ($data) {
                $notificacao = $this->tipoDocumentoRepository->update($data, $data['id']);
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
            if ($value2->$comportamento == true) {
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

    public function atualizaUltimoCodigoTipoDocumento($tipoDocumentoId)
    {
        $buscaUltimoCodigo = $this->tipoDocumentoRepository->find($tipoDocumentoId);
        $request = [
            "ultimo_documento" => $buscaUltimoCodigo->ultimo_documento + 1
        ];
        return $this->update($request, $tipoDocumentoId);
    }

    public function ordenacaoArray(&$array, $column, $direction = SORT_ASC)
    {
        $reference_array = array();
        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }
        array_multisort($reference_array, $direction, $array);
    }
}
