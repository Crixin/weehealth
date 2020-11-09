<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Symfony\Component\HttpFoundation\Session\Session;
use Modules\Core\Model\{User, Cidade, Empresa};
use Modules\Portal\Model\{Processo, EmpresaUser, EmpresaGrupo, EmpresaProcesso, Grupo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\{RESTServices};

class EmpresaController extends Controller
{

    private $ged;

    /*
    * Construtor
    */
    public function __construct()
    {
        $this->ged = new RESTServices();
    }

    public function index()
    {
        $empresas = Empresa::orderBy('nome')->get();
        return view('core::empresa.index', compact('empresas'));
    }

    public function newEnterprise()
    {
        $cidades = $this->getCitiesWithState();
        return view('empresa.create', compact('cidades'));
    }

    public function saveEnterprise(Request $request)
    {
        $validator = Validator::make
        (
            $request->all(),
            [
                'nome'                  => 'required|string|max:100|unique:empresa',
                'cnpj'                  => 'required|string|min:18|max:18',
                'telefone'              => 'required|string|min:14|max:15',
                'responsavel_contato'   => 'required|string|max:50',
                'pasta_ftp'             => 'required|string|max:150',
                'obs'                   => 'max:500',
                'cidade_id'             => 'required|numeric'
            ]
        );

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        $empresa = new Empresa();
        $empresa->nome = $request->get('nome');
        $empresa->cnpj = $request->get('cnpj');
        $empresa->telefone = $request->get('telefone');
        $empresa->responsavel_contato = $request->get('responsavel_contato');
        $empresa->pasta_ftp = $request->get('pasta_ftp');
        $empresa->obs = $request->get('obs');
        $empresa->cidade_id = $request->get('cidade_id');
        $empresa->save();

        Helper::setNotify('Nova empresa criada com sucesso!', 'success|check-circle');
        return redirect()->route('empresa');
    }


    public function editEnterprise($_id)
    {
        $cidades = $this->getCitiesWithState();
        $empresa = Empresa::find($_id);
        return view('empresa.update', compact('cidades', 'empresa'));
    }

    public function updateEnterprise(Request $request)
    {
        $arrRegras = array('cnpj' => 'required|string|min:18|max:18', 'telefone' => 'required|string|min:14|max:15', 'responsavel_contato' => 'required|string|max:100', 'pasta_ftp' => array('required', 'string', 'max:150', "regex:/^[a-zA-Z_\/]*$/"), 'obs' => 'max:500', 'cidade_id' => 'required|numeric');
        $empresa = Empresa::find($request->get('idEmpresa'));

        if ($empresa->nome != $request->get('nome')) {
            $arrRegras['nome'] = 'required|string|max:100|unique:empresa';
        }

        $validator = Validator::make($request->all(), $arrRegras);
        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        $empresa->nome = $request->get('nome');
        $empresa->cnpj = $request->get('cnpj');
        $empresa->telefone = $request->get('telefone');
        $empresa->responsavel_contato = $request->get('responsavel_contato');
        $empresa->pasta_ftp = $request->get('pasta_ftp');
        $empresa->obs = $request->get('obs');
        $empresa->cidade_id = $request->get('cidade_id');
        $empresa->save();

        Helper::setNotify('Informações da empresa atualizadas com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }


    public function linkedUsers($_id)
    {
        $empresa = Empresa::find($_id);

        $usuariosJaVinculados = EmpresaUser::join('users', 'users.id', '=', 'user_id')
                                    ->where('empresa_id', '=', $empresa->id)
                                    ->select(
                                        'empresa_user.id',
                                        'permissao_download',
                                        'permissao_visualizar',
                                        'permissao_impressao',
                                        'permissao_aprovar_doc',
                                        'permissao_excluir_doc',
                                        'permissao_upload_doc',
                                        'permissao_receber_email',
                                        'name',
                                        'email',
                                        'user_id',
                                        'empresa_id',
                                        'permissao_editar'
                                    )->get();
        $idUsuariosJaVinculados = $usuariosJaVinculados->pluck('user_id');
        $usuariosRestantes = User::select('id', 'name')->whereNotIn(
            'id',
            $idUsuariosJaVinculados
        )->orderBy('name')->get();

        return view('empresa.usuarios_vinculados', compact('empresa', 'usuariosJaVinculados', 'usuariosRestantes'));
    }

    public function updateLinkedUsers(Request $request) 
    {
        $empresa = Empresa::find($request->get('idEmpresa'));
        if ($request->usuarios_empresa !== null) {
            foreach ($request->get('usuarios_empresa') as $key => $value) {
                $eu = new EmpresaUser();
                $eu->permissao_visualizar = true;
                $eu->permissao_impressao = false;
                $eu->permissao_download = false;
                $eu->permissao_aprovar_doc = false;
                $eu->permissao_excluir_doc = false;
                $eu->permissao_upload_doc = false;
                $eu->permissao_receber_email = false;
                $eu->empresa_id = $request->get('idEmpresa');
                $eu->user_id = $value;
                $eu->save();
            }
        }

        Helper::setNotify('Usuários vinculados à empresa ' . $empresa->nome . ' atualizados com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }

    public function linkedGroups($_id)
    {
        $empresa = Empresa::find($_id);

        $gruposJaVinculados = EmpresaGrupo::join('grupo', 'grupo.id', '=', 'grupo_id')
                                    ->where('empresa_id', '=', $empresa->id)
                                    ->select
                                    (
                                        'empresa_grupo.id',
                                        'permissao_download',
                                        'permissao_visualizar',
                                        'permissao_impressao',
                                        'permissao_aprovar_doc',
                                        'permissao_excluir_doc',
                                        'permissao_upload_doc',
                                        'permissao_receber_email',
                                        'nome',
                                        'grupo_id',
                                        'empresa_id',
                                        'permissao_editar'
                                    )->get();
        $idGruposJaVinculados = $gruposJaVinculados->pluck('grupo_id');
        $gruposRestantes = Grupo::select('id', 'nome')->whereNotIn('id', $idGruposJaVinculados)->orderBy('nome')->get();

        return view('empresa.grupos_vinculados', compact('empresa', 'gruposJaVinculados', 'gruposRestantes'));
    }


    public function updateLinkedGroups(Request $request)
    {
        $empresa = Empresa::find($request->get('idEmpresa'));

        foreach ($request->get('grupos_empresa') as $key => $value) {
            $eg = new EmpresaGrupo();
            $eg->permissao_visualizar = true;
            $eg->permissao_impressao = false;
            $eg->permissao_download = false;
            $eg->permissao_aprovar_doc = false;
            $eg->permissao_excluir_doc = false;
            $eg->permissao_upload_doc = false;
            $eg->permissao_receber_email = false;
            $eg->empresa_id = $request->get('idEmpresa');
            $eg->grupo_id = $value;
            $eg->save();
        }

        Helper::setNotify('Grupos vinculados à empresa ' . $empresa->nome . ' atualizados com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }

    public function linkedProcesses($_id)
    {
        $empresa = Empresa::find($_id);

        $processosVinculados = EmpresaProcesso::join('processo', 'processo.id', '=', 'empresa_processo.processo_id')->where('empresa_id', '=', $_id)->select('empresa_processo.id', 'empresa_processo.id_area_ged', 'empresa_processo.indice_filtro_utilizado', 'processo.id AS pro_id', 'processo.nome')->get();

        $idProcessosVinculados = $processosVinculados->pluck('pro_id');

        $processosRestantes = Processo::whereNotIn('id', $idProcessosVinculados)->orderBy('nome')->get();

        if (!Session::has('message')) {
            Helper::setNotify(
                'Lembre-se: serão listadas todas as áreas que o usuário ' . env('NAME_GED_USER') . ' possui permissão de acesso no GED!',
                'info|alert-circle'
            );
        }

        $areas = $this->ged->getAreaAreas()['response'];

        $hierarquiaAreas = [];
        $arrayPaiFilho = [];
        $arrayPais = [];

        foreach ($areas as $key => $area) {
            if ($area->idAreaPai) {
                $arrayPaiFilho[$area->idAreaPai][] = $area;
            } else {
                $arrayPais[$area->id] = $area;
            }
        }

        foreach ($arrayPais as $key => $arrayPai) {
            $listaAreas = $this->buscaFilhosAreasGed($arrayPai, $arrayPaiFilho);
        }

        return view(
            'empresa.processos_vinculados',
            compact('empresa', 'processosVinculados', 'processosRestantes', 'listaAreas')
        );
    }


    private function buscaFilhosAreasGed($idAreaPai, $arrayPaiFilho)
    {
        $retorno = [];

        if (array_key_exists($idAreaPai->id, $arrayPaiFilho)) {
            foreach ($arrayPaiFilho[$idAreaPai->id] as $idArea => $info) {
                $nodes = $this->buscaFilhosAreasGed($info, $arrayPaiFilho);

                if ($nodes) {
                    $retorno[] = [
                        'id' => $info->id,
                        'text' => $info->nome,
                        'nodes' => $this->buscaFilhosAreasGed($info, $arrayPaiFilho)
                    ];
                } else {
                    $retorno[] = [
                        'id' => $info->id,
                        'text' => $info->nome,
                    ];
                }
            }
        }
        return $retorno;
    }

    public function updateLinkedProcesses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'processo_selecionado'      => 'required|string',
            'id_area_ged'               => 'required|array',
            'indice_filtro_utilizado'   => 'required|array',
            'headersTable'              => 'required'
        ]);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        $empresa = Empresa::find($request->get('idEmpresa'));
        $processo = Processo::find($request->get('processo_selecionado'));

        $empresaP = new EmpresaProcesso();
        $empresaP->indice_filtro_utilizado = json_encode($request->get('indice_filtro_utilizado'));
        $empresaP->id_area_ged = json_encode($request->get('id_area_ged'));
        $empresaP->empresa_id = (int) $empresa->id;
        $empresaP->processo_id = (int) $processo->id;
        $empresaP->todos_filtros_pesquisaveis = $request->headersTable;
        $empresaP->save();

        Helper::setNotify(
            'Vinculação entre a empresa ' . $empresa->nome . ' e o processo ' . $processo->nome . ' foi executada com sucesso!',
            'success|check-circle'
        );

        return redirect()->back();
    }

    /*=====================================================
                    Métodos Internos
    =====================================================*/

    private function getCitiesWithState()
    {
        $cidades = [];
        $estados = Cidade::select('estado')->orderBy('estado')->groupBy('estado')->get()->pluck('estado')->toArray();
        foreach ($estados as $key => $value) {
            $cidades[$value] = Cidade::where('estado', '=', $value)->get()->pluck('nome', 'id')->toArray();
        }

        return $cidades;
    }
}
