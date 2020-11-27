<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Modules\Core\Model\{User};
use Modules\Portal\Model\{Grupo, GrupoUser};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::orderBy('nome')->get();
        return view('portal::grupo.index', compact('grupos'));
    }

    public function newGroup()
    {
        return view('portal::grupo.create');
    }

    public function saveGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'      => 'required|string|max:100|unique:portal_grupo',
            'descricao' => 'required|string|max:300'
        ]);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        $grupo = new Grupo();
        $grupo->nome = $request->get('nome');
        $grupo->descricao = $request->get('descricao');
        $grupo->save();

        Helper::setNotify('Grupo criado com sucesso!', 'success|check-circle');
        return redirect()->route('portal.grupo');
    }


    public function editGroup($_id)
    {
        $grupo = Grupo::find($_id);
        return view('portal::grupo.update', compact('grupo'));
    }


    public function updateGroup(Request $request)
    {
        $arrRegras = array('descricao' => 'required|string|max:300');
        $grupo = Grupo::find($request->get('idGrupo'));

        if ($request->get('nome') != $grupo->nome) {
            $arrRegras['nome'] = 'required|string|max:100|unique:portal_grupo';
        }
        $validator = Validator::make($request->all(), $arrRegras);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        $grupo->nome = $request->get('nome');
        $grupo->descricao = $request->get('descricao');
        $grupo->save();

        Helper::setNotify('Informações do grupo alteradas com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }


    public function linkedUsers($_id)
    {
        $grupo = Grupo::find($_id);
        $todosUsuarios = User::select('id', 'name')->orderBy('name')->get();
        return view('portal::grupo.usuarios_vinculados', compact('grupo', 'todosUsuarios'));
    }


    public function updateLinkedUsers(Request $request)
    {
        $grupo = Grupo::find($request->get('idGrupo'));

        // Deleta todos usuários que já estão vinculados ao grupo
        foreach ($grupo->users as $key => $user) {
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
}
