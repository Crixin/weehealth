<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\SetorRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;
use Modules\Core\Repositories\UserRepository;

class SetorController extends Controller
{
    protected $setorRepository;
    protected $userRepository;

    public function __construct(SetorRepository $setorRepository, UserRepository $userRepository)
    {
        $this->setorRepository = $setorRepository;
        $this->userRepository  = $userRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $setores = $this->setorRepository->findAll();
        return view('core::setor.index', compact('setores'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::setor.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
        $cadastro = self::montaRequest($request);
        try {
            DB::transaction(function () use ($cadastro) {
                $this->setorRepository->create($cadastro);
            });

            Helper::setNotify('Novo setor criado com sucesso!', 'success|check-circle');
            return redirect()->route('core.setor');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o setor', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $setor = $this->setorRepository->find($id);
        return view('core::setor.update', compact('setor'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $setor = $request->get('idSetor');
        $update  = self::montaRequest($request);
        try {
            DB::transaction(function () use ($update, $setor) {
                $this->setorRepository->update($update, $setor);
            });

            Helper::setNotify('Informações do setor atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o setor', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request = $request->id;
        try {
            DB::transaction(function () use ($id) {
                $this->setorRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function linkedUsers($_id)
    {
        $setor = $this->setorRepository->find($_id);
        $todosUsuarios = $this->userRepository->findBy(
            [
                ['setor_id', '=', null],
                ['setor_id', '=', $_id, 'or']
            ],
            [],
            [
                ['name', 'ASC']
            ]
        );
        return view('core::setor.usuarios_vinculados', compact('setor', 'todosUsuarios'));
    }


    public function updateLinkedUsers(Request $request)
    {
        $setor = $this->setorRepository->find($request->get('idSetor'));

        if ($request->usuarios_setor !== null) {
            foreach ($request->get('usuarios_setor') as $key => $value) {
                $update = ['setor_id' => $request->get('idSetor')];
                $this->userRepository->update($update, $value);
            }
        }

        Helper::setNotify('Usuários vinculados ao setor ' . $setor->nome . ' atualizados com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }

    public function validador(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome'               => empty($request->get('idSetor')) ? 'required|string|min:5|max:100|unique:core_setor' : '',
                'descricao'          => 'required|string|min:5|max:200',
                'sigla'              => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }

    public function montaRequest(Request $request)
    {
        return [
            "nome"      => $request->get('nome'),
            "descricao" => $request->get('descricao'),
            "sigla"     => $request->get('sigla'),

        ];
    }
}
