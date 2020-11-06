<?php

namespace Modules\Portal\Http\Middleware;

use Closure;
use Modules\Portal\Model\EmpresaProcesso;
use App\Classes\{GEDServices, Helper};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Auth, Log};

class UserCanByEnterpriseMiddleware
{
    private $ged;

    /*
    * Construtor
    */
    public function __construct()
    {
        $this->ged = new GEDServices(['id_user' => env('ID_GED_USER'), 'server' => env('URL_GED_WSDL')]);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userCanAccess = $this->checkPermission($request);

        return $next($request);
        //if($userCanAccess) return $next($request);
        //else return response()->view('errors.403');
    }

    /**
     * Faz a diferenciação de qual método foi será utilizado com base no verbo HTTP e, com isso, possibilita uma verificação específica se o processo atual, vinculado à uma empresa, que o usuário está tentando acessar, faz parte do grupo de processos que ele REALMENTE pode acessar.
     *
     * @param \Illuminate\Http\Request $_request    A requisição que foi criada através da ação do usuário
     * @return boolean
     */
    private function checkPermission($_request) {
        $getMethods    = array(
            'processo.buscar',
            'processo.listarDocumentos',
            'processo.acessarDocumento',
            'processo.upload'
        );
        $posttMethods  = array(
            'processo.listarRegistros',
            'processo.documento.aprovar',
            'processo.documento.rejeitar',
            'processo.realizarUpload'
        );
        $userProcesses = Helper::getUserProcesses()->pluck('processos', 'id');

        $routeDetails = $_request->route()->getAction();
        if (in_array($routeDetails['as'], $getMethods)) {
            $allowed = $this->analyzeParams($_request, $userProcesses);
        } else {
            $allowed = $this->analyzeBody($_request, $userProcesses);
        }
        //dd();
        $allowed = true;
        return $allowed;
    }


    /**
     * Realiza as verificações necessárias, em cima dos métodos GET, para saber se nenhuma regra ou
     * vinculação do sistema está sendo violada e, então, permite que o usuário siga com sua requisição.
     * @param array $_userProcesses Todos os processos que o usuário tem permissão de acessar (chaveados pelo id da empresa, ou seja, o id da empresa "dona" dos processos é a chave do array)
     * @param \Illuminate\Http\Request $_request A requisição que foi criada através da ação do usuário
     * @return boolean
     */
    private function analyzeParams($_request, $_userProcesses)
    {
        $params = $_request->route()->parameters();
        $found  = false;

        if (array_key_exists('idEmpresa', $params) && array_key_exists('idProcesso', $params)) {

            $enterpriseProcess = EmpresaProcesso::where('empresa_id', $params['idEmpresa'])->where('processo_id', $params['idProcesso'])->first();
            $found = $this->isProcessContained($_userProcesses, $enterpriseProcess);
        } else {
            if (array_key_exists('_idRegistro', $params)) {
                try {
                    $record = $this->ged->pesquisaRegistro("", $params['_idRegistro'], false, true)->return;
                    $enterpriseProcess = EmpresaProcesso::where('id_area_ged', $record->idArea)->first();
                    $found = $this->isProcessContained($_userProcesses, $enterpriseProcess);
                } catch (\Throwable $th) {
                    Log::error("Erro ao validar o acesso do usuário {" . Auth::user()->id . "} a todos documentos do registro: " . $params['_idRegistro']);
                }
            } elseif (array_key_exists('_idDocumento', $params)) {
                try {
                    $document = $this->ged->pesquisaDocumento($params['_idDocumento'])->return;
                    $enterpriseProcess = EmpresaProcesso::where('id_area_ged', $document->idArea)->first();
                    $found = $this->isProcessContained($_userProcesses, $enterpriseProcess);
                } catch (\Throwable $th) {
                    Log::error("Erro ao validar o acesso do usuário {" . Auth::user()->id . "} ao documento: " . $params['_idDocumento']);
                }
            }
        }
        return $found;
    }


    /**
     * Realiza as verificações necessárias, em cima dos métodos POST, para saber se nenhuma regra ou vinculação do sistema está sendo violada e, então, permite que o usuário siga com sua requisição.
     *
     * @param array $_userProcesses                 Todos os processos que o usuário tem permissão de acessar (chaveados pelo id da empresa, ou seja, o id da empresa "dona" dos processos é a chave do array)
     * @param \Illuminate\Http\Request $_request    A requisição que foi criada através da ação do usuário
     * @return boolean
     */
    private function analyzeBody($_request, $_userProcesses)
    {
        $found = false;

        if (array_key_exists('idAreaGED', $_request->all()) || array_key_exists('id-documento', $_request->all())) {
            if (array_key_exists('id-documento', $_request->all())) {
                try {
                    $document = $this->ged->pesquisaDocumento($_request->get('id-documento'))->return;
                    $enterpriseProcess = EmpresaProcesso::where('id_area_ged', $document->idArea)->first();
                    $found = $this->isProcessContained($_userProcesses, $enterpriseProcess);
                } catch (\Throwable $th) {
                    Log::error("Erro ao validar a permissão de aprovar/rejeitar do usuário {" . Auth::user()->id . "} ao documento: " . $_request->get('id-documento'));
                }
            } else {
                try {
                    $enterpriseProcess = EmpresaProcesso::where('id_area_ged', $_request->idAreaGED)->first();
                    $found = $this->isProcessContained($_userProcesses, $enterpriseProcess);
                } catch (\Throwable $th) {
                    Log::error("Erro ao validar o acesso do usuário {" . Auth::user()->id . "} aos registros da área: " . $identifiers['idAreaGED']);
                }
            }
        } else {
            // Aqui é considerado "seguro" utilizar a sessão porque ela é populada apenas se o usuário seguir os passos esperados pela aplicação; os valores da sessão ficam armazenados em um arquivo no disco; e o método aqui é POST, então não é qualquer usuário que tem o conhecimento de tentar 'hackear' a requisição.
            try {
                $identifiers = session('identificadores');
                $enterpriseProcess = EmpresaProcesso::where('id_area_ged', $identifiers['idAreaGED'])->first();
                $found = $this->isProcessContained($_userProcesses, $enterpriseProcess);
            } catch (\Throwable $th) {
                Log::error("Erro ao validar o acesso do usuário {" . Auth::user()->id . "} aos registros da área: " . $identifiers['idAreaGED']);
            }
        }

        return $found;
    }


    /**
     * Verifica se o processo que o usuário está tentando utilizar está contido dentro dos processos que ele tem permissão de acessar (em virtude do nível de permissão e dos vínculos que ele tem com as empresas)
     *
     * @param array $_userProcesses                     Todos os processos que o usuário tem permissão de acessar (chaveados pelo id da empresa, ou seja, o id da empresa "dona" dos processos é a chave do array)
     * @param App\EmpresaProcesso $_enterpriseProcess   As propriedades do processo que o usuário está tentando acessar naquele momento (conterá id da empresa e do processo)
     * @return boolean
     */
    private function isProcessContained($_userProcesses, $_enterpriseProcess)
    {
        $found = false;
        if (Arr::exists($_userProcesses, $_enterpriseProcess->empresa_id)) {
            foreach ($_userProcesses[$_enterpriseProcess->empresa_id] as $process) {
                if ($process->id == $_enterpriseProcess->processo_id) {
                    $found = true;
                    break;
                }
            }
        }
        return $found;
    }
}
