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
        $menuModules = (array) json_decode(file_get_contents(base_path() . '/menu.json'));
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
        try {
            $errors = $this->validator($request);
            
            if ($errors) {
                return redirect()->back()->withErrors($errors)->withInput();
            }

            DB::transaction(function () use ($request) {
                
                $nome = $request->nome;
                
                $permissoes = $request->all();
                unset($permissoes["_token"]);
                unset($permissoes["nome"]);
                $permissoes = array_keys($permissoes);

                $perfil = $this->perfilRepository->create([
                    'nome' => $nome,
                    'permissoes' => $permissoes
                ]);
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
        $error = $this->validator($request, $id);
        if (!$error) {
            return redirect()->back()->withInput()->compact(['error' => $error]);
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $nome = $request->nome;
                
                $permissoes = $request->all();
                unset($permissoes["_token"]);
                unset($permissoes["nome"]);
                $permissoes = array_keys($permissoes);

                $this->perfilRepository->update(
                    [
                        'nome' => $nome,
                        'permissoes' => $permissoes
                    ],
                    $id
                );


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
    public function validator(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'nome' => 'sometimes|required|string|unique:core_perfil,nome',
        ]);
        if ($validator->fails()) {
            Helper::setNotify($validator->messages(), 'danger|close-circle');
            return $validator;
        }
        return false;
    }
}
