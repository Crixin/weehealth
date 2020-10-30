<?php

namespace App\Http\Controllers;

use Helper;
use ZipArchive;
use App\Empresa;
use App\Classes\Constants;
use App\Classes\RESTServices;
use App\Jobs\MakeZipFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{

    private $ged;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->ged = new RESTServices();
    }

    
    public function index()
    {
        $empresas = Empresa::whereNotNull('pasta_ftp')->orderBy("nome")->get()->pluck('nome', 'id')->toArray();
        return view('download.index', compact('empresas'));
    }


    public function makeZIP(Request $request)
    {
        if (empty($request->matricula)  &&  empty($request->cpf)) {
            Helper::setNotify('Você deve preencher ao menos um dos campos!', 'danger|close-circle');
            return back();
        }

        $zipName = "";
        $request->validate(['empresa_id' => 'required|int']);
        $empresa = Empresa::find($request->empresa_id);
        $configuracoes = Helper::getInitialConfigs('download_ftp');
        $ftpBasePath = Helper::getFTPBasePath();

        $arrIdsArquivos = [];
        if (!empty($request->matricula)) {
            $zipName = 'M' . $request->matricula . "_";
            
            $arrIdsArquivos['folhas_ponto'] = $this->getListaIdsDocumentos(
                $configuracoes['folha_ponto'],
                $request->matricula,
                Constants::$PROCESSOS[1]
            );
        }
        
        if (!empty($request->cpf)) {
            $zipName .= 'C' . preg_replace('/[^0-9]/', '', $request->cpf) . "_";
            
            $arrIdsArquivos['documentos'] = $this->getListaIdsDocumentos(
                $configuracoes['demais_processos'],
                $request->cpf,
                Constants::$PROCESSOS[0]
            );
        }

        $zipName .= time() . '.zip';
        
        // Chama o job para colocar essa criação em uma queue e retorna para o usuário
        MakeZipFileJob::dispatch($arrIdsArquivos, $zipName, $empresa->pasta_ftp);
        
        return back()->with(['nome_zip' => $zipName, 'pasta' => $empresa->pasta_ftp, 'ftpBasePath' => $ftpBasePath]);

        // Código se desejasse fazer o download
        //$headers = array( 'Content-Type' => 'application/octet-stream', );
        //return response()->download($filetopath, $zipFileName, $headers);
    }


    private function getListaIdsDocumentos($_listaIdAreas, $_valorPesquisa, $_processo) {
        $idsDocumentos = [];
        $possuiVinculos = false;

        if ($_processo == Constants::$PROCESSOS[0]) {
            $filtrosDefinidos = Constants::$FILTER_OPTIONS_GED[0][0];
            $possuiVinculos = true;
            $pesquisarVinculo = [];
        } else {
            $filtrosDefinidos = Constants::$FILTER_OPTIONS_GED[1]['matricula'];
        }
        
        $arrIndicesDePesquisa = $this->indexesPush($filtrosDefinidos, $_valorPesquisa);
        foreach ($_listaIdAreas as $key => $idArea) {
            $arrPesquisa = [
                'listaIdArea' => [
                    $idArea
                ],
                'listaIndice' => [
                    (object) [
                       $arrIndicesDePesquisa
                    ]
                ]
            ];

            $registros = $this->ged->post(env('GED_URL') . "/registro/pesquisa", $arrPesquisa);
            dd($idArea);

            $this->findDocumentId($idsDocumentos, $registros, $idArea);
            
            if (!empty($registros->id)) {
                $registros = [$registros];
            } else {
                $registros = ( is_array($registros) && count($registros) > 0 ) ? $registros : null;
            }


            if (!empty($registros)) {
                foreach ($registros as $key => $registro) {
                    $registroCompleto = $this->ged->post(env('GED_URL') . "/registro/pesquisa" . $idArea . '/' . $registro->id)['response'];
                    dd($registroCompleto);
                    //$registroCompleto = $this->ged->pesquisaRegistro($idArea, $registro->id, false, true);

                    if ($possuiVinculos) {
                        $vinculo = $this->indexesPush(Constants::$FILTROS_VINCULO, $registro->id);
                        array_push($pesquisarVinculo, $vinculo);
                    }

                    $listaDocumentos = (is_array($registroCompleto->return->listaDocumento)) ? $registroCompleto->return->listaDocumento : [$registroCompleto->return->listaDocumento];
                    
                    foreach ($listaDocumentos as $key => $documento) {
                        $idsDocumentos[$documento->endereco] = $documento->id;
                    }
                }
            }
        }

        // Trecho implantado para garantir que documentos de áreas "filhas" (vínculo) sejam encontrados
        if ($possuiVinculos) {
            foreach ($pesquisarVinculo as $indicesPesquisa) {
                foreach ($_listaIdAreas as $idArea) {
                    $registros = $this->ged->pesquisaRegistros($idArea, $indicesPesquisa, 0, 100);
                    $this->findDocumentId($idsDocumentos, $registros, $idArea);
                }
            }
        }

        return $idsDocumentos;
    }


    private function indexesPush(array $filters, $value)
    {
        $array = [];
        array_push($array, [
            'descricao' => $filters['descricao'],
            'idTipoIndice' => $filters['idTipoIndice'],
            'identificador' => $filters['identificador'],
            'valor' => $value
        ]);

        return $array;
    }


    private function findDocumentId(&$idsDocumentos, $registros, $idArea)
    {
        if (!empty($registros->id)) {
            $registros = [$registros];
        } else {
            $registros = ( is_array($registros) && count($registros) > 0 ) ? $registros : null;
        }

        if (!empty($registros)) {
            foreach ($registros as $registro) {
                $registros = $this->ged->post(env('GED_URL') . "/registro/pesquisa", $arrPesquisa)['response'];

                $registroCompleto = $this->ged->post($idArea, $registro->id, false, true);
                $listaDocumentos = (is_array($registroCompleto->return->listaDocumento)) ? $registroCompleto->return->listaDocumento : [$registroCompleto->return->listaDocumento];
                
                foreach ($listaDocumentos as $documento) {
                    $idsDocumentos[$documento->endereco] = $documento->id;
                }
            }
        }
    }
}
