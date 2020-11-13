<?php

namespace App\Console\Commands;

use App\Logs;
use Carbon\Carbon;
use App\Classes\{Constants, GEDServices, Helper, MySQL};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;

class ProcessaLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melhora performace log sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $START_DATE = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $END_DATE   = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $MYSQL = new MySQL('localhost', 'root', 'speed');
        $GED   = new GEDServices(['id_user' => env('ID_GED_USER'), 'server' => env('URL_GED_WSDL')]);

        $this->writeLogFile("==> Buscando ações realizadas em documentos entre: [ $START_DATE | $END_DATE ]");

        // Conecta no MySQL do GED para capturar os logs
        try {
            $settings = Helper::getInitialConfigs('download_ftp');
            $areasId = Arr::flatten($settings);
            $rows = $this->getGedLogs($START_DATE, $END_DATE, $MYSQL);

            if (!is_null($rows)) {
                while ($row = $rows->fetch_assoc()) {
                    $log = [
                        'acao'       => $row['acao'],
                        'referencia' => $row['referencia'],
                        'idUsuario'  => $row['idUsuario'],
                        'data'       => $row['data'],
                    ];

                    if ($row['acao'] == Constants::$ACAO_GED_INSERIR  &&  empty($row['idObjeto'])) {
                        $docDetails  = explode(',', $row['observacoes']);
                        $areaId      = explode('idArea = ', $docDetails[0])[1];

                        // Se não estiver nesse array, não é uma das áreas utilizadas pelo sistema
                        if (in_array($areaId, $areasId)) {
                            $registerId  = explode('idRegistro = ', $docDetails[1])[1];
                            $processName = explode('nome da area = ', $docDetails[2])[1];

                            $register     = $GED->pesquisaRegistro($areaId, $registerId, false, true)->return;
                            $documentList = ( is_array($register->listaDocumento) ) ? $register->listaDocumento : [$register->listaDocumento];
                            
                            $log['nomeProcesso'] = $processName;
                            $log['idArea']       = $areaId;
                            $log['idRegistro']   = $registerId;
                            $log['descricao']    = "Documento inserido: o arquivo foi anexado no registro!";
                            
                            foreach ($documentList as $document) {
                                $databaseDocument = Logs::where('acao', Constants::$ACAO_GED_INSERIR)
                                    ->where('idArea', $document->idArea)->where('idRegistro', $document->idRegistro)
                                    ->where('idDocumento', $document->id)->first();

                                $docCreationDate = Carbon::createFromFormat(
                                    'd/m/Y H:i',
                                    $document->listaIndice[3]->valor
                                )->format('Y-m-d H:i:s');

                                if (is_null($databaseDocument) && ($docCreationDate >= $START_DATE && $docCreationDate <= $END_DATE)) {
                                    $thisDocumentLog = $log;
                                    $thisDocumentLog['idDocumento'] = $document->id;

                                    foreach ($document->listaIndice as $documentIndex) {
                                        if ($documentIndex->identificador == Constants::$IDENTIFICADOR_TIPO_DOCUMENTO) {
                                            $thisDocumentLog['complemento'] = "Tipo do Documento: $documentIndex->valor";
                                        }
                                    }

                                    $thisDocumentLog['valor'] = $this->makeRecordValue($document->idArea, $document->idRegistro, $GED);
                                    $this->insertLogsTable($thisDocumentLog);
                                }
                            }
                        }
                    } elseif ($row['acao'] == Constants::$ACAO_GED_ALTERAR && !empty($row['idObjeto'])) {
                        $docDetails = $GED->pesquisaDocumento($row['idObjeto'])->return;

                        // Se não estiver nesse array, não é uma das áreas utilizadas pelo sistema
                        if (in_array($docDetails->idArea, $areasId)) {
                            foreach ($docDetails->listaIndice as $docIndex) {
                                if ($docIndex->identificador == Constants::$IDENTIFICADOR_TIPO_DOCUMENTO) {
                                    $log['descricao'] = "Documento atualizado: $docIndex->valor foi substituído!";
                                }
                            }

                            $log['nomeProcesso'] = explode('nome da area = ', explode(',', $row['observacoes'])[2])[1];
                            $log['idArea'] = $docDetails->idArea;
                            $log['idRegistro'] = $docDetails->idRegistro;
                            $log['idDocumento'] = $docDetails->id;
                            $log['complemento'] = "Versão atual: $docDetails->versao";
                            $log['valor'] = $this->makeRecordValue($docDetails->idArea, $docDetails->idRegistro, $GED);
                            
                            $this->insertLogsTable($log);
                        }
                    }
                }
                
                $this->writeLogFile("Todas ações iteradas. Nenhum problema detectado. Finalizando...\n\n");
            } else {
                $this->writeLogFile("Nenhuma ação com documentos executada neste dia! Finalizando...\n\n");
            }
        } catch (\Exception $e) {
            $this->writeLogFile("Houve um problema durante a execução da tarefa (Exception)!\n\n");
            Log::error("Houve um problema durante a execução da tarefa (Exception)!");
            Log::error($e);
        } catch (\Throwable $th) {
            $this->writeLogFile("Houve um problema durante a execução da tarefa (Throwable)!\n\n");
            Log::error("Houve um problema durante a execução da tarefa (Throwable)!");
            Log::error($th);
        }

        $MYSQL->closeConnection();
    }

    /**
     * Realiza um select no banco de dados do GED e obtém todos os logs que atendem ao filtro.
     *
     * @param string $_startDate
     * @param string $_endDate
     * @param MySQL $_mysql
     *
     * @return array
     */
    private function getGedLogs(string $_startDate, string $_endDate, MySQL $_mysql)
    {
        $query = "SELECT * FROM ctx_logusuario 
                    WHERE acao IN ('alterar', 'inserir') AND 
                            (idUsuario = 1) AND 
                            (data >= '$_startDate' AND data <= '$_endDate') AND 
                            referencia = 'documento' 
                    ORDER BY `ctx_logusuario`.`id` DESC";

        $logs = $_mysql->select($query);
        return $logs;
    }


    /**
     * Insere um registro na tabela de logs do banco local do sistema (PostgreSQL).
     *
     * @param array $log
     * @return void
     */
    private function insertLogsTable(array $log)
    {
        return Logs::create($log);
    }


    /**
     * Escreve logs no arquivo 'store_ged_logs' para que seja mais fácil acompanhar quais resultados foram obtidos.
     *
     * @param string $message
     * @return void
     */
    private function writeLogFile(string $message)
    {
        $PATH = storage_path('logs');
        $FILE = 'store_ged_logs.log';

        file_put_contents(
            $PATH . '/' . $FILE,
            "### WEE_LOG [" . now() . "] ### " . $message . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }


    /**
     * Constrói o valor do registro que deve ser gravado na coluna 'valor' da tabela de logs
     *  (tratando casos normais e de vínculo, pois, nos vínculos, o valor fica na área pai).
     *
     * @param [type] $_idArea
     * @param [type] $_idRegistro
     * @param GEDServices $_ged
     *
     * @return string
     */
    private function makeRecordValue($_idArea, $_idRegistro, GEDServices $_ged)
    {
        $register = $_ged->pesquisaRegistro($_idArea, $_idRegistro, false, true)->return;
        $register->listaIndice = array_filter($register->listaIndice, function ($v, $k) {
            return !in_array($v->identificador, Constants::$INDICES_OCULTOS_LOGS);
        }, ARRAY_FILTER_USE_BOTH);
        
        $value = "";
        foreach ($register->listaIndice as $index) {
            if ($index->idTipoIndice == 17) {
                $currentArea  = $_ged->pesquisarArea($register->idArea)->return;
                $parentRecord = $_ged->pesquisaRegistro($currentArea->idAreaPai, $register->listaIndice[3]->valor, false, true)->return;

                $parentRecord->listaIndice = array_filter($parentRecord->listaIndice, function ($v, $k) {
                    return !in_array($v->identificador, Constants::$INDICES_OCULTOS_LOGS);
                }, ARRAY_FILTER_USE_BOTH);

                foreach ($parentRecord->listaIndice as $childIndex) {
                    $content = property_exists($childIndex, 'valor') ? $childIndex->valor : '';
                    $value .= ($value === "") ? $childIndex->descricao . ": " . $content : ";" . $childIndex->descricao . ": " . $content;
                }
            } else {
                $content = property_exists($index, 'valor') ? $index->valor : '';
                $value .= ($value === "") ? $index->descricao . ": " . $content : ";" . $index->descricao . ": " . $content;
            }
        }

        return $value;
    }
}
