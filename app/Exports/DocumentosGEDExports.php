<?php

namespace App\Exports;

use App\Classes\RESTServices;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DocumentosGEDExports implements WithMultipleSheets
{

    protected $nomeProcesso;

    protected $idArea;
    
    protected $cpfPesquisa;


    public function __construct(string $nomeProcesso, string $idArea, string $cpfPesquisa)
    {
        $this->nomeProcesso = $nomeProcesso;
        $this->idArea = $idArea;
        $this->cpfPesquisa = $cpfPesquisa;
    }

    //caso queira mais uma folha no relatório é só chamar outro DocumentosGEDSheet para o array de sheets
    public function sheets(): array
    {

        $sheets[] = new DocumentosGEDSheet($this->nomeProcesso, $this->idArea, $this->cpfPesquisa);

        return $sheets;
    }
}
