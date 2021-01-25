<?php

namespace Modules\Portal\Http\Controllers;

use Session;
use App\Classes\{Helper, RESTServices};
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\{EmpresaRepository};
use Modules\Portal\Repositories\{EmpresaProcessoRepository, ProcessoRepository};
use Illuminate\Support\Facades\Validator;

class EmpresaProcessoController extends Controller
{

    protected $empresaRepository;
    protected $empresaProcessoRepository;
    protected $processoRepository;
    private $ged;

    public function __construct(
        EmpresaRepository $empresaRepository,
        EmpresaProcessoRepository $empresaProcessoRepository,
        ProcessoRepository $processoRepository
    )
    {
        $this->empresaRepository         = $empresaRepository;
        $this->empresaProcessoRepository = $empresaProcessoRepository;
        $this->processoRepository        = $processoRepository;

        $this->ged = new RESTServices();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('portal::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($_id)
    {
        $empresa = $this->empresaRepository->find($_id);
        $processosVinculados = $this->empresaProcessoRepository->findBy(
            [
                ['empresa_id', '=', $_id]
            ]
        );
        $idProcessosVinculados = $processosVinculados->pluck('processo_id');
        $processosRestantes = $this->processoRepository->findBy(
            [
                ['id','',$idProcessosVinculados,'NOTIN']
            ],
            [],
            ['nome','asc']
        );


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

        return view('portal::empresaProcesso.processos_vinculados',
            compact('empresa', 'processosVinculados', 'processosRestantes', 'listaAreas')
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('portal::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('portal::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
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

        $empresa = $this->empresaRepository->find($request->get('idEmpresa'));
        $processo = $this->processoRepository->find($request->get('processo_selecionado'));

        $empresaP = $this->empresaProcessoRepository->create(
            [
                "indice_filtro_utilizado" => json_encode($request->get('indice_filtro_utilizado')),
                "id_area_ged" => json_encode($request->get('id_area_ged')),
                "empresa_id" => (int) $empresa->id,
                "processo_id" => (int) $processo->id,
                "todos_filtros_pesquisaveis" => $request->headersTable
            ]
        );

        Helper::setNotify(
            'Vinculação entre a empresa ' . $empresa->nome . ' e o processo ' . $processo->nome . ' foi executada com sucesso!',
            'success|check-circle'
        );

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
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
}
