<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Modules\Core\Model\{User, Grupo, GrupoUser};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\GrupoRepository;

class GrupoController extends Controller
{
    protected $grupoRepository;

    public function __construct(GrupoRepository $gruporepository)
    {
        $this->grupoRepository = $gruporepository;
    }

    public function index()
    {
        $grupos = $this->grupoRepository->findBy(
            [],
            [],
            [
                ['nome','ASC']
            ]
        );
        return view('core::grupo.index', compact('grupos'));
    }

    public function newGroup()
    {
        return view('core::grupo.create');
    }

    public function saveGroup(Request $request)
    {
        self::validador($request);
        $cadastro = self::montaRequest($request);
        try {
            DB::transaction(function () use ($cadastro, $request) {
                $grupo = $this->grupoRepository->create($cadastro);
            });
            Helper::setNotify('Grupo criado com sucesso!', 'success|check-circle');
            return redirect()->route('core.grupo');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao gravar o grupo.', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    public function editGroup($_id)
    {
        $grupo = $this->grupoRepository->find($_id);
        return view('core::grupo.update', compact('grupo'));
    }


    public function updateGroup(Request $request)
    {
        $id = $request->get('idGrupo');
        self::validador($request);
        $update = self::montaRequest($request);
        try {
            DB::transaction(function () use ($update, $id) {
                $grupo = $this->grupoRepository->update($update, $id);
            });
            Helper::setNotify('Grupo alterado com sucesso!', 'success|check-circle');
            return redirect()->route('core.grupo');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao alterar o grupo.', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }


    public function linkedUsers($_id)
    {
        $grupo = Grupo::find($_id);
        $todosUsuarios = User::select('id', 'name')->orderBy('name')->get();
        return view('core::grupo.usuarios_vinculados', compact('grupo', 'todosUsuarios'));
    }


    public function updateLinkedUsers(Request $request)
    {
        $grupo = Grupo::find($request->get('idGrupo'));

        // Deleta todos usuários que já estão vinculados ao grupo
        foreach ($grupo->coreUsers as $key => $user) {
            $user->pivot->delete();
        }

        if ($request->usuarios_grupo !== null) {
            foreach ($request->get('usuarios_grupo') as $key => $value) {
                $gu = new GrupoUser();
                $gu->grupo_id = (int) $request->get('idGrupo');
                $gu->user_id = $value;
                $gu->save();
            }
        }

        Helper::setNotify('Usuários vinculados ao grupo ' . $grupo->nome . ' atualizados com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }

    public function validador(Request $request)
    {
        $validator = Validator::make
        (
            $request->all(),
            [
                'nome'      => empty($request->get('idGrupo')) ? 'required|string|max:100|unique:core_grupo' : '',
                'descricao' => 'required|string|max:300'
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
            "descricao" => $request->get('descricao'),
            "sigla" => (strlen($request->get('nome')) >= 3) ? strtoupper(substr($request->get('nome'), 0, 3)) : "SIGLA"
        ];
    }
}
