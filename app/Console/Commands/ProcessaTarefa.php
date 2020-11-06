<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Log, Storage, File};
use App\Classes\{Constants, RESTServices};
use Modules\Portal\Repositories\{ConfiguracaoTarefaRepository, TarefaRepository};
use Exception;

class ProcessaTarefa extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'command:tarefa {tarefa}';
    
    protected $configTarefa;
    protected $tarefas;
    protected $ged;
    protected $idTipoVinculo;
    
    
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
    public function __construct(ConfiguracaoTarefaRepository $configTarefa, TarefaRepository $tarefas)
    {
        parent::__construct();
        $this->configTarefa = $configTarefa;
        $this->tarefas = $tarefas;
        $this->idTipoVinculo = 17;
        $this->ged = new RESTServices();
    }
    
    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $tarefa = $this->tarefas->find($this->argument('tarefa'), ['configuracaoTarefa']);

        if ($tarefa->status == "RODANDO") {
            return;
        }

        $this->tarefas->update([
            'status' => 'RODANDO'
        ], $tarefa->id);

        
        $indices = json_decode($tarefa->indices, true)['indice'];
        
        $indices = array_filter($indices, function ($arr) {
            return $arr['posicao'] ? $arr : false;
        });

        $config = $tarefa->configuracaoTarefa;

        [$files, $pathForFolders] = $this->getFilesAndPath($tarefa);

        $filesPath = $pathForFolders . $tarefa->pasta;
        
        $rejectFilesPath = $pathForFolders . $tarefa->pasta_rejeitados;
        foreach ($files as $file) {
            $fileName = basename($file);

            $partsFileName = explode($tarefa->identificador, $fileName);

            foreach ($indices as $key => $indice) {
                $indices[$key]['valor'] = $partsFileName[$indice['posicao'] - 1];
            }
            switch ($tarefa->tipo_indexacao) {
                case 'REGISTRO':
                    try {
                        $areaGed = json_decode($tarefa['area'])[0];

                        $filtroPesquisa = $this->montaFiltro($areaGed, $indices);
                        
                        //BUSCA O REGISTRO PARA O UPLOAD DOS ARQUIVOS
                        $registros = $this->ged->pesquisaRegistro($filtroPesquisa);

                        if ($registros['error']) {
                            throw new Exception("Erro na pesquisa");
                        }
                        
                        $registros = $registros['response'];
                        
                        //POSSUI REGISTRO
                        if ($registros->totalResultadoPesquisa) {
                            $registro = $registros->listaRegistro[0];
                            $idRegistro = $registro->id;

                            $indicesUpdate = array_values(array_filter($indices, function ($indice) {
                                return $indice['autoupdate'] ? $indice : false;
                            }));
                            if (!empty($indicesUpdate)) {
                                foreach ($registro->listaIndice as $indiceReg) {
                                    $keyIndiceUpdate = array_search(
                                        $indiceReg->identificador,
                                        array_column($indicesUpdate, "identificador")
                                    );
                                    
                                    if ($keyIndiceUpdate !== false) {
                                        $indiceReg->valor = $indicesUpdate[$keyIndiceUpdate]['valor'];
                                    }
                                }
                            }

                            $update = $this->ged->putRegistro(json_decode(json_encode($registro), true));
                            if ($update['error']) {
                                throw new Exception("Erro na pesquisa");
                            }
                        } else {
                            //CRIA O REGISTRO
                            $newRegister = $this->createNewRegister($areaGed, $filtroPesquisa['listaIndice']);
                            if (!$newRegister) {
                                throw new Exception("Erro na pesquisa");
                            }
                            $idRegistro = $newRegister;
                        }

                        $base64file = base64_encode(file_get_contents($filesPath . '/' . $fileName));
                        
                        $insereDocumento = [
                            'idArea' => $areaGed,
                            'idRegistro' => $idRegistro,
                            'endereco' => $fileName,
                            'idUsuario' => env('ID_GED_USER'),
                            'bytes'    => $base64file,
                            'removido' => false
                        ];
                        
                        $documentoInserido = $this->ged->post(env('GED_URL') . "/documento", $insereDocumento);
                        if ($documentoInserido['error']) {
                            //MOVE TO REJECTS
                            rename($filesPath . "/" . $fileName, $rejectFilesPath . "/" . $fileName);
                        } else {
                            //DELETE FILE
                            unlink($filesPath . "/" . $fileName);
                        }
                    } catch (\Throwable $th) {
                        rename($filesPath . "/" . $fileName, $rejectFilesPath . "/" . $fileName);
                    }
                    break;
                
                case 'DOCUMENTO':
                    //
                    break;
                
                default:
                    //
                    break;
            }
        }
        $this->tarefas->update([
            'status' => 'PARADA'
        ], $tarefa->id);
    }


    //PEGA OS INDECES INDEXADORES (ULITIZADOS PARA BUSCAR O REGISTRO PARA UPLOAD DOS ARQUIVOS)
    private function montaFiltro($_area, $_indices)
    {
        try {
            foreach ($_indices as $indice) {
                if ($indice['indexador']) {
                    // SE TIPO VÍNCULO
                    if ($indice['tipoIndice'] == $this->idTipoVinculo) {
                        $infoArea = $this->ged->buscaInfoArea($_area);
                        
                        if ($infoArea['error']) {
                            throw new Exception("Erro na pesquisa");
                        }

                        foreach ($infoArea['response'][0]->listaIndicesRegistro as $indicesAr) {
                            if ($this->idTipoVinculo == $indicesAr->idTipoIndice && $indicesAr->identificador == $indice['identificador']) {
                                $areaReferenciada = $indicesAr->idAreaReferenciada;
                                $indicesAreaRelacionada = $indicesAr;
                            }
                        }

                        $infoAreaReferenciada = $this->ged->buscaInfoArea($areaReferenciada);

                        if ($infoAreaReferenciada['error']) {
                            throw new Exception("Erro na pesquisa");
                        }
                                
                        foreach ($infoAreaReferenciada['response'][0]->listaIndicesRegistro as $indicesArRef) {
                            if ($indicesArRef->identificador == $indice['identificador']) {
                                $indiceVincArRef = $indicesArRef;
                            }
                        }

                        $listaIndice = [
                            (object) [
                                'idTipoIndice' => $indiceVincArRef->idTipoIndice,
                                'identificador' => $indiceVincArRef->identificador,
                                'valor' => $indice['valor']
                            ]
                        ];

                        $buscaRegistro = $this->ged->pesquisaRegistro([
                            'listaIdArea' => [
                                $areaReferenciada
                            ],
                            'listaIndice' => $listaIndice,
                            "removido" => false
                        ]);

                        
                        if ($buscaRegistro['error']) {
                            throw new Exception("Erro na pesquisa");
                        }
                        
                        if (!$buscaRegistro['response']->listaRegistro) {
                            $indice['valor'] = $this->createNewRegister($areaReferenciada, $listaIndice);
                        } else {
                            $indice['valor'] = $buscaRegistro['response']->listaRegistro[0]->id;
                        }
                    }

                    $arrayFilters[] = (object) [
                        'descricao' => $indice['nomeIndice'],
                        'idTipoIndice' => $indice['tipoIndice'],
                        'identificador' => $indice['identificador'],
                        'valor' => $indice['valor']
                    ];
                }
            }
            
            return [
                'listaIdArea' => [
                    $_area
                ],
                'listaIndice' => $arrayFilters,
                "removido" => false
            ];
        } catch (\Throwable $th) {
            return $th;
        }
    }


    private function createNewRegister(string $_area, array $_indices)
    {
        try {
            foreach ($_indices as $indice) {
                $indicesNewRegis[] = (object) [
                    "idTipoIndice" => $indice->idTipoIndice,
                    "identificador" => $indice->identificador,
                    "valor" => $indice->valor
                ];
            }
            
            $newRegister = [
                "idArea" => $_area,
                "removido" => false,
                "listaIndice" => $indicesNewRegis
            ];
            
            $create = $this->ged->postRegistro($newRegister);
            return $create['error'] ? false : $create['response'];
        } catch (\Throwable $th) {
            return false;
        }
    }


    private function getFilesAndPath($_task)
    {
        $config = $_task->configuracaoTarefa;

        switch ($config->tipo) {
            case 'FTP':
                $ftp = Storage::createFtpDriver([
                    'host'     => $config->ip,
                    'username' => $config->usuario,
                    'password' => $config->senha,
                    'port'     => $config->porta,
                    'timeout'  => 30,
                ]);

                return [
                    $ftp->allFiles('ftp-teste'),
                    'ftp://' . $config->usuario . ':' . $config->senha . "@" . $config->ip . '/'
                ];
                break;
            
            case 'PASTA_SERVIDOR':
                $files = File::Files($config->caminho . $_task->pasta);
                return [
                    $files,
                    $config->caminho
                ];
                break;
            
            default:
                # code...
                break;
        }
    }
}
