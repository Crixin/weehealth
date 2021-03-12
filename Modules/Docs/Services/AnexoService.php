<?php

namespace Modules\Docs\Services;

use App\Classes\RESTServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\AnexoRepository;
use Modules\Docs\Repositories\DocumentoRepository;

class AnexoService
{
    protected $anexoRepository;
    protected $documentoRepository;
    protected $parametrorepository;

    public function __construct()
    {
        $this->anexoRepository = new AnexoRepository();
        $this->documentoRepository = new DocumentoRepository();
        $this->parametroRepository = new ParametroRepository();
    }

    public function create(array $dados)
    {
        return $this->anexoRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->anexoRepository->update($dados, $id);
    }

    public function delete($delete)
    {
        return  $this->anexoRepository->delete($delete);
    }

    public function createAnexoGED(array $data)
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
                        'valor' => 'Anexo'
                    ]
                ]
            ];
            $response = $ged->postDocumento($insereAnexo);
            $idRegistro = $response['response'];

            return ["success" => true, "data" => $idRegistro];
        } catch (\Throwable $th) {
            dd($th);
            return ["success" => false];
        }
    }

    public function deleteAnexoGED(string $idGedAnexo)
    {
        try {
            $ged = new RESTServices();
            $response = $ged->deleteDocumento($idGedAnexo);
            $idRegistro = $response['response'];

            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            return ["success" => false];
        }
    }

    public function processaAnexo(int $idDocumento)
    {
        try {
            $buscaAnexos  = $this->anexoRepository->findBy(
                [
                    ['documento_id', '=', $idDocumento]
                ]
            );
            foreach ($buscaAnexos as $key => $anexo) {
                if ($anexo->ged_documento_id == null) {
                    $data = [
                        'idDocumento' => $idDocumento,
                        'base64' => $anexo->anexo_documento,
                        'nome' => $anexo->nome,
                        'extensao' => $anexo->extensao
                    ];
                    $response = $this->createAnexoGED($data);
                    if (!$response['success']) {
                        throw new \Exception("Falha ao salvar anexo do documento no GED.");
                    }
                    $idRegistro = $response['data'];

                    $this->update(["ged_documento_id" => $idRegistro, "anexo_documento" => ''], $anexo->id);
                }
            }
            return ["success" => true];
        } catch (\Throwable $th) {
            return ["success" => false];
        }
    }

    public function criaCopiaAnexos(array $data)
    {
        try {
            $anexo = $this->anexoRepository->find($data['id']);
            $ged = new RESTServices();
            $nomeDocumentoFinal = $anexo->nome . "." . $anexo->extensao;
            if ($anexo->ged_documento_id != '') {
                $response = $ged->getDocumento($anexo->ged_documento_id, ['docs' => 'true']);
                if ($response['error']) {
                    throw new \Exception("Falha na busca o anexo para visualização");
                }

                $documentoToClone = $response['response'];
                $storagePath = Storage::disk('weecode_office')->put('/anexos/' . $nomeDocumentoFinal, base64_decode($documentoToClone->bytes));

            } else {
                $storagePath = Storage::disk('weecode_office')->put('/anexos/' . $nomeDocumentoFinal, base64_decode($anexo->anexo_documento));
            }

            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            return ["success" => false];
        }
    }
}
