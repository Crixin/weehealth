<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Portal\Repositories\{EmpresaProcessoRepository};
use App\Classes\{RESTServices};

class UserCan
{
    private $params;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next, $verificacao)
    {
        $this->params = empty($request->all()) ? $request->route()->parameters() : $request->all();

        switch ($verificacao) {
            case 'processo':
                $response = $this->verificaProcesso();
                break;
            case 'registroGed':
                $response = $this->verificaRegistroGed();
                break;
            case 'documentoGed':
                $response = $this->verificaDocumentoGed();
                break;
            
            default:
                # code...
                break;
        }
        
        if (!$response) {
            return response()->view('errors.403');
        }

        return $next($request);
    }


    private function verificaProcesso()
    {
        $empresaProcessoRepository = new EmpresaProcessoRepository();

        $resp = false;

        $idEmpresa = $this->params['idEmpresa'] ?? "";
        $idProcesso = $this->params['idProcesso'] ?? "";

        $userProcesses = \Helper::getUserProcesses()->pluck('processos', 'id');

        if (isset($userProcesses[$idEmpresa])) {
            foreach ($userProcesses[$idEmpresa] as $process) {
                if ($process->id == $idProcesso) {
                    $resp = true;
                    break;
                }
            }
        }

        return $resp;
    }


    private function verificaRegistroGed()
    {
        $ged = new RESTServices();
        $empresaProcessoRepository = new EmpresaProcessoRepository();
        
        $idRegistro = $this->params['idRegistro'];
        
        ['error' => $error, 'response' => $registro] = $ged->getRegistro($idRegistro);

        //CASO O USUÁRIO ALTERE O ID DO REGISTRO PARA UM INEXISTENTE
        if ($error) {
            return false;
        }
        $idArea = $registro->idArea;

        $empresaProcesso = $empresaProcessoRepository->findOneBy(
            [
                ['id_area_ged', 'like', '%' . $idArea . '%']
            ]
        );

        return boolval($empresaProcesso);
    }

    private function verificaDocumentoGed()
    {
        $ged = new RESTServices();
        $empresaProcessoRepository = new EmpresaProcessoRepository();
        
        $idDocumento = $this->params['idDocumento'];
        
        ['error' => $error, 'response' => $documento] = $ged->getDocumento($idDocumento);

        //CASO O USUÁRIO ALTERE O ID DO DOCUMENTO PARA UM INEXISTENTE
        if ($error) {
            return false;
        }

        $idArea = $documento->idArea;

        $empresaProcesso = $empresaProcessoRepository->findOneBy(
            [
                ['id_area_ged', 'like', '%' . $idArea . '%']
            ]
        );

        return boolval($empresaProcesso);
    }
}
