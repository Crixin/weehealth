<?php

namespace Modules\Portal\Http\Controllers;

use Modules\Portal\Model\{EmpresaGrupo, EmpresaUser};
use App\Exports\DocumentosGEDExports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Repositories\{EmpresaRepository, GrupoUserRepository};
use Modules\Portal\Repositories\{
    EmpresaGrupoRepository,
    EmpresaProcessoRepository,
    EmpresaUserRepository,
    ProcessoRepository
};

class RelatorioDocumentosController extends Controller
{

    protected $empresaRepository;
    protected $grupoUserRepository;
    protected $empresaGrupoRepository;
    protected $empresaProcessoRepository;
    protected $empresaUserRepository;
    protected $processoRepository;

    public function __construct(
        EmpresaRepository $empresaRepository,
        GrupoUserRepository $grupoUserRepository,
        EmpresaGrupoRepository $empresaGrupoRepository,
        EmpresaProcessoRepository $empresaProcessoRepository,
        EmpresaUserRepository $empresaUserRepository,
        ProcessoRepository $processoRepository
    )
    {
        $this->empresaRepository = $empresaRepository;
        $this->grupoUserRepository = $grupoUserRepository;
        $this->empresaGrupoRepository = $empresaGrupoRepository;
        $this->empresaProcessoRepository = $empresaProcessoRepository;
        $this->empresaUserRepository = $empresaUserRepository;
        $this->processoRepository = $processoRepository;
    }

    public function index()
    {
        $empresasGrupo = array();

        $grupos = $this->grupoUserRepository->findBy(
            [
                ['user_id','=',Auth::user()->id]
            ]
        );

        foreach ($grupos as $grupo) {
            $empresasGrupo = EmpresaGrupo::where("grupo_id", $grupo->grupo_id)->pluck('empresa_id');
        }

        $empresasUser = EmpresaUser::where('user_id', Auth::user()->id)->pluck('empresa_id');

        $empresasArray = $empresasUser->merge($empresasGrupo)->toArray();

        $empresas = $this->empresaRepository->findBy(
            [
                ['id','',$empresasArray,'IN']
            ]
        );
        return view('portal::relatorio-documentos.index', compact('empresas'));
    }

    public function gerar(Request $request)
    {
        $info = explode(";", $request->processo);

        $empProcesso = $this->empresaProcessoRepository->findBy(
            [
                ['processo_id','=', $info[1], 'AND'],
                ['empresa_id', '=', $info[0]]
            ]
        );
        $processo = $this->processoRepository->find($info[1]);

        return Excel::download(new DocumentosGEDExports($processo->nome, $empProcesso->id_area_ged, $request->cpf ?? ""), $processo->nome . '.xlsx');
    }
}
