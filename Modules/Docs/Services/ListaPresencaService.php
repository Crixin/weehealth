<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Classes\RESTServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\ListaPresencaRepository;

class ListaPresencaService
{

    protected $listaPresencaRepository;
    protected $parametroRepository;
    protected $documentoRepository;

    public function __construct()
    {
        $this->listaPresencaRepository = new ListaPresencaRepository();
        $this->parametroRepository = new ParametroRepository();
        $this->documentoRepository = new DocumentoRepository();
    }

    public function store(array $data)
    {
        try {
            DB::transaction(function () use ($data) {
                $this->listaPresencaRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao avançar a etapa. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $dados, int $id)
    {
        return $this->listaPresencaRepository->update($dados, $id);
    }

    public function createListaPresencaGED(array $data)
    {
        try {
            $ged = new RESTServices();
            $documento = $this->documentoRepository->find($data['idDocumento']);
            $areaGed = $this->parametroRepository->getParametro('AREA_GED_DOCUMENTOS');

            $insereAnexo = [
                'endereco' => $data['nome'] . "." . $data['extensao'],
                'idArea' => $areaGed,
                'idRegistro' => $documento->ged_registro_id,
                'idUsuario' => env('ID_GED_USER'),
                'removido' => false,
                'bytes'    => $data['base64'],
                'listaIndice' => [
                    (object) [
                        'idTipoIndice' => 12,
                        'identificador' => 'tipo',
                        'valor' => 'Lista de Presenca'
                    ]
                ]
            ];
            $response = $ged->postDocumento($insereAnexo);
            $idRegistro = $response['response'];

            return ["success" => true, "data" => $idRegistro];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }

    public function processaListaPresenca(int $idDocumento, string $revisao)
    {
        try {
            $buscaListaPresenca  = $this->listaPresencaRepository->findBy(
                [
                    ["documento_id", "=", $idDocumento],
                    ["revisao_documento", "=", $revisao, "AND"],
                    ["ged_documento_id", "=", '', "AND"]
                ]
            );
            foreach ($buscaListaPresenca as $key => $listaPresenca) {

                $data = [
                    'idDocumento' => $idDocumento,
                    'base64'      => $listaPresenca->lista_presenca_documento,
                    'nome'        => $listaPresenca->nome,
                    'extensao'    => $listaPresenca->extensao
                ];
                $response = $this->createListaPresencaGED($data);
                if (!$response['success']) {
                    throw new \Exception("Falha ao salvar lista de presença no GED.");
                }
                $idRegistro = $response['data'];

                $this->update(["ged_documento_id" => $idRegistro, "lista_presenca_documento" => ''], $listaPresenca->id);
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }

    public function criaCopiaListaPresenca(array $data)
    {
        try {
            $listaPresenca = $this->listaPresencaRepository->find($data['id']);
            $ged = new RESTServices();
            $nomeDocumentoFinal = $listaPresenca->nome . "." . $listaPresenca->extensao;

            if ($listaPresenca->ged_documento_id  != '') {
                $response = $ged->getDocumento($listaPresenca->ged_documento_id, ['docs' => 'true']);
                if ($response['error']) {
                    throw new \Exception("Falha na busca da lista de presença para visualização");
                }
                $documentoToClone = $response['response'];
                $storagePath = Storage::disk('weecode_office')->put('/lista-presenca/' . $nomeDocumentoFinal, base64_decode($documentoToClone->bytes));

            } else {
                $storagePath = Storage::disk('weecode_office')->put('/lista-presenca/' . $nomeDocumentoFinal, base64_decode($listaPresenca->lista_presenca_documento));
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            return ["success" => false];
        }
    }
}
