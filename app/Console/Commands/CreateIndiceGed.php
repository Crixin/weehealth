<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\{Constants, RESTServices};

class CreateIndiceGed extends Command
{
    protected $signature = 'command:cria-indices {area}';
    
    protected $ged;
    
    
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Verifica a necessidade de execução das tarefas agendas';
    
    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->ged = new RESTServices();
    }
    
    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $area = $this->argument('area');

        $areasErros = [];

        if ($area == "todas") {
        /*
            $areas = $this->ged->getAreaAreas();
            foreach ($areas['response'] as $key => $area) {
                $indice = [
                    "idArea" => $area->id,
                    "idAreaReferenciada" => "",
                    "descricao" => "Observação Descritiva",
                    "idTipoIndice" => 16,
                    "identificador" => "OBSERVACAO_DESCRITIVA",
                    "unico" => false,
                    "alteravel" => true,
                    "protegidoPeloSistema" => false,
                    "preenchimentoHabilitado" => true,
                    "preenchimentoObrigatorio" => false,
                    "utilizadoParaBusca" => true,
                    "exportavel" => false,
                    "somenteCadastro" => false,
                    "utilizadoParaAssociacao" => false,
                    "exibidoNaPesquisa" => true,
                    "ordenavel" => false,
                    "exibidoNoEmail" => false,
                ];
                $result = $this->ged->postIndice($area->id . "?localizacao=REGISTRO", $indice);
                if ($result['error']) {
                    array_push($areasErros, $area);
                }
            }*/
        } else {
            $indice = [
                "idArea" => $area,
                "idAreaReferenciada" => "",
                "descricao" => "Observação Descritiva",
                "idTipoIndice" => 16,
                "identificador" => "OBSERVACAO_DESCRITIVA",
                "unico" => false,
                "alteravel" => true,
                "protegidoPeloSistema" => false,
                "preenchimentoHabilitado" => true,
                "preenchimentoObrigatorio" => false,
                "utilizadoParaBusca" => true,
                "exportavel" => false,
                "somenteCadastro" => false,
                "utilizadoParaAssociacao" => false,
                "exibidoNaPesquisa" => true,
                "ordenavel" => false,
                "exibidoNoEmail" => false,
            ];
            
            $result = $this->ged->postIndice($area . "?localizacao=REGISTRO", $indice);

            if ($result['error']) {
                array_push($areasErros, $area);
            }
        }
        dd($areasErros);
    }
}
