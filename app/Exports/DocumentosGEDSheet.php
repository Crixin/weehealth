<?php

namespace App\Exports;

use App\Classes\Helper;
use App\Classes\RESTServices;
use Maatwebsite\Excel\Concerns\{FromArray, WithHeadings, ShouldAutoSize, WithTitle };

class DocumentosGEDSheet implements FromArray, WithHeadings, ShouldAutoSize, WithTitle
{

    private $REST;
    
    protected $header = array();
    
    protected $nomeProcesso;

    protected $idArea;

    protected $cpfPesquisa;


    public function __construct(string $nomeProcesso, string $idArea, string $cpfPesquisa)
    {
        $this->nomeProcesso = $nomeProcesso;
        $this->idArea = $idArea;
        $this->cpfPesquisa = preg_replace('/[^0-9]/', '', $cpfPesquisa);
        $this->REST = new RESTServices();
    }

    private function buscaCPF($registers)
    {
        foreach ($registers->listaIndice ?? [] as $key => $indices) {
            if ($indices->identificador == "cpf") {
                if (Helper::validaCPF($indices->valor)) {
                    return $indices->valor;
                } else {
                    return $this->buscaCPF($this->REST->get(env("GED_URL") . "/registro/" . $indices->valor)['response']);
                }
            }
        }
        return false;
    }

    public function array(): array
    {
        $resultArray = array();
        $body = [
            "listaIdArea" => [
                $this->idArea
            ],
            "listaIndice" => [
                (object) []
            ],
            "inicio" => 0,
            "fim" => 10000000
        ];
        
        $cpf = "";
        $registros = $this->REST->post(env("GED_URL") . "/registro/pesquisa", $body)['response'];
        foreach ($registros->listaRegistro ?? [] as $registro) {
            $registersWithDocs = $this->REST->get(env("GED_URL") . "/registro/" . $registro->id . "?docs=true&bytes=false")['response'];
            
            if (!$registersWithDocs->removido) {
                $result = array_fill(0, count($this->header), '');
                
                $cpf = $this->buscaCPF($registersWithDocs);
                
                if ($this->cpfPesquisa == "" || $this->cpfPesquisa == $cpf) {
                    $valor = "";
                    $keyArray = "";
                    $result[0] = $cpf;
                    foreach ($registersWithDocs->listaDocumento ?? [] as $key => $documentos) {
                        foreach ($documentos->listaIndice as $key => $indiceDoc) {
                            switch ($indiceDoc->identificador) {
                                case "Data_do_registro":
                                    $valor = $indiceDoc->valor;
                                    break;
                                case "Tipo":
                                    $keyArray = array_search($indiceDoc->valor, $this->header);
                                    break;
                            }

                            if ($valor && $keyArray) {
                                $result[$keyArray] = $valor;
                                $valor = "";
                                $keyArray = "";
                            }
                        }
                    }
                    $resultArray[] = $result;
                }
            }
            // Termina a pesquisa caso tenha filtro de cpf e o mesmo já foi encontrado
            if ($cpf) {
                if ($this->cpfPesquisa == $cpf) {
                    break;
                }
            }
        }
        return $resultArray;
    }


    public function headings(): array
    {
        $this->header = array("N° CPF");

        $area = $this->REST->get(env("GED_URL") . "/area/" . $this->idArea)['response'];

        foreach ($area ?? [] as $info) {
            foreach ($info->listaIndiceDocumento as $indiceDoc) {
                if ($indiceDoc->idTipoIndice == 12 && $indiceDoc->identificador == "Tipo") {
                    foreach ($indiceDoc->listaMultivalorado as $multivalorado) {
                        array_push($this->header, $multivalorado->descricao);
                    }
                }
            }
        }
        return $this->header;
    }


    public function title(): string
    {
        return $this->nomeProcesso;
    }
}
