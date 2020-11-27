<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Modules\Core\Model\{Cidade};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{ParametroRepository, EmpresaRepository, EmpresaTipoRepository};

class EmpresaController extends Controller
{
    private $parametroRepository;
    private $empresaRepository;
    private $empresaTipoRepository;

    /*
    * Construtor
    */
    public function __construct(ParametroRepository $parametroRepository, EmpresaRepository $empresaRepository, EmpresaTipoRepository $empresaTipoRepository)
    {
        $this->parametroRepository = $parametroRepository;
        $this->empresaRepository = $empresaRepository;
        $this->empresaTipoRepository = $empresaTipoRepository;
    }

    public function index()
    {
        $empresas = $this->empresaRepository->findBy([], [], [['nome','=','asc']]);
        return view('core::empresa.index', compact('empresas'));
    }

    public function newEnterprise()
    {
        $cidades = $this->getCitiesWithState();
        $tipos   = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','TIPO_EMPRESA']
            ]
        );
        $tiposEmpresa = json_decode($tipos->valor_padrao);
        return view('core::empresa.create', compact('cidades', 'tiposEmpresa'));
    }

    public function saveEnterprise(Request $request)
    {
        self::validador($request);

        $cadastro = self::montaRequest($request);
        try {
            DB::transaction(function () use ($cadastro, $request) {
                $empresa = $this->empresaRepository->create($cadastro);
                foreach ($request->tipo_empresa as $key => $tipo) {
                    $this->empresaTipoRepository->create(["empresa_id" => $empresa->id,"tipo_id"  => $tipo]);
                }
            });
            Helper::setNotify('Nova empresa criada com sucesso!', 'success|check-circle');
            return redirect()->route('core.empresa');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar a empresa o registro', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }


    public function editEnterprise($_id)
    {
        $cidades = $this->getCitiesWithState();
        $empresa = $this->empresaRepository->find($_id);
        $tipos   = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','TIPO_EMPRESA']
            ]
        );
        $tiposEmpresa = json_decode($tipos->valor_padrao);
        $buscaTiposSelecionados = $this->empresaTipoRepository->findBy(
            [
                ['empresa_id','=',$empresa->id]
            ]
        );
        $tipoSelecionados = [];
        foreach ($buscaTiposSelecionados as $key => $value) {
            array_push($tipoSelecionados, $value->tipo_id);
        }
        return view('core::empresa.update', compact('cidades', 'empresa', 'tiposEmpresa', 'tipoSelecionados'));
    }

    public function updateEnterprise(Request $request)
    {
        self::validador($request);
        $empresa = $request->get('idEmpresa');
        $update  = self::montaRequest($request);
        try {
            DB::transaction(function () use ($update, $empresa, $request) {
                $this->empresaRepository->update($update, $empresa);

                $tiposEmpresa = $this->empresaTipoRepository->findBy([['empresa_id','=',$empresa]]);
                $id_tipos = array();
                foreach ($tiposEmpresa as $key => $value) {
                    array_push($id_tipos, $value->tipo_id);
                }
                $diff_para_create  = array_diff($request->tipo_empresa, $id_tipos);
                $diff_para_detete = array_diff($id_tipos, $request->tipo_empresa);

                foreach ($diff_para_create as $key => $tipo) {
                    $this->empresaTipoRepository->create(["empresa_id" => $empresa,"tipo_id"  => $tipo]);
                }

                foreach ($diff_para_detete as $key => $tipo) {
                    $this->empresaTipoRepository->delete(
                        [
                            ['empresa_id','=',$empresa],
                            ['tipo_id','=',$tipo,"AND"]
                        ]
                    );
                }
            });

            Helper::setNotify('Informações da empresa atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar a empresa', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
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


    public function validador(Request $request)
    {
        $validator = Validator::make
        (
            $request->all(),
            [
                'nome'                  => empty($request->get('idEmpresa')) ? 'required|string|max:100|unique:core_empresa' : '',
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

        return true;
    }

    public function montaRequest(Request $request)
    {
        return [
            "nome" => $request->get('nome'),
            "cnpj" => $request->get('cnpj'),
            "telefone" => $request->get('telefone'),
            "responsavel_contato" => $request->get('responsavel_contato'),
            "pasta_ftp" => $request->get('pasta_ftp'),
            "obs" => $request->get('obs'),
            "cidade_id" => $request->get('cidade_id')
        ];
    }
}
