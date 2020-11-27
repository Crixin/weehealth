<?php

namespace Modules\Portal\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Portal\Repositories\{
    GrupoRepository,
    EmpresaGrupoRepository,
    EmpresaProcessoGrupoRepository,
    EmpresaProcessoRepository
};
use Illuminate\Support\Facades\{DB, Validator};
use App\Classes\{Constants, Helper};

class EmpresaProcessoGrupoController extends Controller
{

    private $grupoRepository;
    private $empresaGrupoRepository;
    private $empresaProcessoGrupoRepository;
    private $empresaProcessoRepository;

    public function __construct(
        GrupoRepository $grupo,
        EmpresaGrupoRepository $empresaGrupo,
        EmpresaProcessoGrupoRepository $empresaProcessoGrupo,
        EmpresaProcessoRepository $empresaProcesso
    ) {
        $this->grupoRepository = $grupo;
        $this->empresaGrupoRepository = $empresaGrupo;
        $this->empresaProcessoGrupoRepository = $empresaProcessoGrupo;
        $this->empresaProcessoRepository = $empresaProcesso;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($_empresaProcessoId)
    {
        $grupos = $this->grupoRepository->findAll();
        $empresaProcessoGrupo = $this->empresaProcessoGrupoRepository->findBy(
            [
                ['empresa_processo_id', '=', $_empresaProcessoId]
            ],
            ['portalEmpresaProcesso', 'coreGrupo'],
            [['portal_grupo.nome']],
        );

        $empresaProcesso = $this->empresaProcessoRepository->find($_empresaProcessoId);

        $gruposVinculados = array_column($empresaProcessoGrupo->toArray(), 'grupo_id');

        $gruposNaoVinculados = array_filter($grupos->toArray(), function ($grupo) use ($gruposVinculados) {
            return !in_array($grupo['id'], $gruposVinculados);
        });
        $gruposVinculados = $empresaProcessoGrupo;

        $cabecalho = [];
        foreach (json_decode($gruposVinculados[0]->portalEmpresaProcesso->indice_filtro_utilizado ?? "[]") as $key => $indice) {
            $indice = json_decode($indice);
            array_push($cabecalho, $indice->descricao);
        }

        $tipoIndicesGED = Constants::$OPTIONS_TYPE_INDICES_GED;
        return view(
            'portal::processo-grupo.vinculo-grupos-filtros',
            compact(
                'empresaProcessoGrupo',
                'gruposNaoVinculados',
                'gruposVinculados',
                'empresaProcesso',
                'cabecalho',
                'tipoIndicesGED'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'grupos_a_vincular' => 'present|array'
        ]);

        if ($validator->fails()) {
            Helper::setNotify("Selecione os grupos para vincular!", 'danger|close-circle');
            return redirect()->back()->withInput();
        }
        DB::beginTransaction();

        try {
            foreach ($_request->grupos_a_vincular as $grupo) {
                if (!$this->empresaProcessoGrupoRepository->findOneBy([['grupo_id', "=", $grupo], ['empresa_processo_id', "=", $_request->empresaProcessoId]])) {
                    $this->empresaProcessoGrupoRepository->create([
                        'grupo_id' => $grupo,
                        'empresa_processo_id' => $_request->empresaProcessoId,
                        'filtros' => json_encode([])
                    ]);
                }
            }
            DB::commit();
            Helper::setNotify('Vínculos criado com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao alterar o registro', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource. 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $_request)
    {

        DB::beginTransaction();

        try {
            foreach (json_decode($_request->filters) as $key => $filter) {
                $this->empresaProcessoGrupoRepository->update([
                    'filtros' => json_encode($filter)
                ], $key);
            }
            DB::commit();
            Helper::setNotify('Informações alteradas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao alterar o registro', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
