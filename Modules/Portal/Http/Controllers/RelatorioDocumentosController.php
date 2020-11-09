<?php

namespace Modules\Portal\Http\Controllers;

use Modules\Core\Model\{Empresa};
use Modules\Portal\Model\{EmpresaGrupo, EmpresaProcesso, EmpresaUser, GrupoUser, Processo};
use App\Exports\DocumentosGEDExports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioDocumentosController extends Controller
{

    public function index()
    {
        $empresasGrupo = array();

        $grupos = GrupoUser::where('user_id', Auth::user()->id)->get();

        foreach ($grupos as $grupo) {
            $empresasGrupo = EmpresaGrupo::where("grupo_id", $grupo->grupo_id)->pluck('empresa_id');
        }

        $empresasUser = EmpresaUser::where('user_id', Auth::user()->id)->pluck('empresa_id');

        $empresasArray = $empresasUser->merge($empresasGrupo)->toArray();

        $empresas = Empresa::whereIn('id', $empresasArray)->get();

        return view('portal::relatorio-documentos.index', compact('empresas'));
    }

    public function gerar(Request $request)
    {
        $info = explode(";", $request->processo);

        $empProcesso = EmpresaProcesso::where('processo_id', $info[1])->where('empresa_id', $info[0])->first();
        $processo = Processo::find($info[1]);

        return Excel::download(new DocumentosGEDExports($processo->nome, $empProcesso->id_area_ged, $request->cpf ?? ""), $processo->nome . '.xlsx');
    }
}
