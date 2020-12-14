<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, DB};
use Modules\Core\Repositories\{PerfilRepository};

class PerfilController extends Controller
{
    protected $perfilRepository;


    public function __construct(PerfilRepository $perfil)
    {
        $this->perfilRepository = $perfil;
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
        $permissoes = [];
        return view('core::perfil.create', compact('permissoes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $_request)
    {
        try {
            $permissoes = $this->permissaoRepository->findAll([], [['descricao', 'ASC']]);

            [$result, $errors] = $this->validator($_request);
            if (!$result) {
                return redirect()->back()->withErrors($errors)->withInput();
            }
    
            DB::transaction(function () use ($_request) {
                $perfil = $this->perfilRepository->create(['nome' => $_request->nome]);
                foreach ($_request->permissoes as $key => $permissao) {
                    $this->perfilPermissaoRepository->create([
                        'perfil_id' => $perfil->id,
                        'permissao_id' => $permissao
                    ]);
                }
            });

            Helper::setNotify('Novo perfil criado com sucesso!', 'success|check-circle');
            return redirect()->route('core.perfil');
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao criar o perfil", 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perfil = $this->perfilRepository->find($id, ['corePermissoes']);
        $userPermissao = [];

        $userPermissao = [];
        $permissoes = [];

        return view('core::perfil.update', compact('perfil', 'permissoes', 'userPermissao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $_request, $id)
    {
        [$result, $error] = $this->validator($_request, $id);
        if (!$result) {
            return redirect()->back()->withInput()->compact(['error' => $error]);
        }

        try {
            DB::transaction(function () use ($_request, $id) {
                $this->perfilRepository->update(['nome' => $_request->nome], $id);
                $perfil = $this->perfilRepository->find($id, ['corePermissoes']);
                $userPermissao = [];

                foreach ($perfil->corePermissoes as $key => $value) {
                    $userPermissao[] = $value->pivot->permissao_id;
                }

                $deleteArray = array_diff($userPermissao, $_request->permissoes);
                $createArray = array_diff($_request->permissoes, $userPermissao);
            });
            Helper::setNotify('Perfil atualizado com sucesso!', 'success|check-circle');
            return redirect()->route('core.perfil');
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao atualizar o perfil", 'danger|close-circle');
            return redirect()->back()->withInput();
        }
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

   /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Responsevalidator
     */
    public function validator(Request $_request, $id = "")
    {
        $validator = Validator::make($_request->all(), [
            'nome' => 'required|string|unique:core_perfil,nome' . ($id ? ',' . $id : ''),
            'permissoes' => 'required|exists:core_permissao,id',
        ]);
        if ($validator->fails()) {
            Helper::setNotify($validator->messages(), 'danger|close-circle');
            return [false, $validator];
        }
        return true;
    }
}
