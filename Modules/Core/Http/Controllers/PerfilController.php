<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB};
use Modules\Core\Repositories\{PerfilRepository};
use Modules\Core\Services\PerfilService;

class PerfilController extends Controller
{
    protected $perfilRepository;

    public function __construct()
    {
        $this->perfilRepository = new PerfilRepository();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perfis = $this->perfilRepository->findAll(['coreUsers']);
        return view('core::perfil.index', compact('perfis'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = array_keys(\Module::allEnabled());

        return view('core::perfil.create', compact('modules'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $perfilService = new PerfilService();
        $nome = $request->nome;

        $permissoes = $request->all();
        unset($permissoes["_token"], $permissoes["nome"]);
        $permissoes = array_keys($permissoes);

        $data = [
            'nome' => $nome,
            'permissoes' => $permissoes,
        ];

        $reponse = $perfilService->store($data);

        if (!$reponse['success']) {
            return redirect()->back()->withInput();
        }

        if ($reponse) {
            Helper::setNotify('Novo perfil criado com sucesso!', 'success|check-circle');
            return redirect()->route('core.perfil');
        }

        Helper::setNotify("Erro ao criar o perfil. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perfil = $this->perfilRepository->find($id);
        $modules = array_keys(\Module::allEnabled());

        return view('core::perfil.update', compact('perfil', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $perfilService = new PerfilService();
        $nome = $request->nome;

        $permissoes = $request->all();
        unset($permissoes["_token"], $permissoes["nome"]);
        $permissoes = array_keys($permissoes);

        $data = [
            'id' => $id,
            'nome' => $nome,
            'permissoes' => $permissoes,
        ];

        $reponse = $perfilService->update($data);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        }

        if ($reponse) {
            Helper::setNotify('Perfil atualizado com sucesso!', 'success|check-circle');
            return redirect()->route('core.perfil');
        }

        Helper::setNotify("Erro ao atualizar o perfil. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $_request)
    {
        try {
            DB::transaction(function () use ($_request) {
                $this->perfilRepository->delete($_request->id);
            });
            Helper::setNotify('Perfil atualizado com sucesso!', 'success|check-circle');
            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao excluir o perfil, verifique se não existem usuários vinculados a esse perfil", 'danger|close-circle');
            return response()->json(['response' => 'erro']);
        }
    }


 /*    public function validator($data)
    {
        $validator = Validator::make($data->all(), [
            'nome' => 'sometimes|required|string|unique:core_perfil,nome',
        ]);
        if ($validator->fails()) {
            Helper::setNotify($validator->messages(), 'danger|close-circle');
            return $validator;
        }
        return false;
    } */
}
