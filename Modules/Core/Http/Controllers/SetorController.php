<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;
use Exception;
use Modules\Core\Repositories\{SetorRepository, UserRepository};
use Modules\Core\Services\SetorService;

class SetorController extends Controller
{
    protected $setorRepository;
    protected $userRepository;

    public function __construct()
    {
        $this->setorRepository = new SetorRepository();
        $this->userRepository  = new UserRepository();
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
        $setorService = new SetorService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $setorService->store($montaRequest);
        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Novo setor criado com sucesso!', 'success|check-circle');
            return redirect()->route('core.setor');
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
        $achouDocumentos = $setor->docsDocumento->count() > 0 ? true : false ;
        return view('core::setor.update', compact('setor', 'achouDocumentos'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $setorService = new SetorService();
        $montaRequest = $this->montaRequest($request);

        $setor = $this->setorRepository->find($request->idSetor);
        //Caso tem documento vinculado nao atualiza a sigla
        if ($setor->docsDocumento->count() > 0) {
            $montaRequest['sigla'] = $setor->sigla;
        }

        if ($setor->coreUsers->count() > 0 && $setor->inativo == 0 && empty($request->inativo)) {
            Helper::setNotify("Erro ao inativar setor. Setor possui usuários vinculados", 'danger|close-circle');
            return redirect()->route('core.setor');
        }

        $reponse = $setorService->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Setor atualizado com sucesso!', 'success|check-circle');
            return redirect()->route('core.setor');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request->setor_id;
        try {
            $setorService = new SetorService();
            $return = $setorService->delete($id);

            if (!$return['success']) {
                throw new Exception($return['message'], 1);
            }
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro', 'message' => $th->getMessage()]);
        }
    }

    public function linkedUsers($_id)
    {
        $setor = $this->setorRepository->find($_id);
        $todosUsuarios = $this->userRepository->findBy(
            [
                ['setor_id', '=', null],
                ['setor_id', '=', $_id, 'or'],
                ['inativo', '=', 0, "AND"]
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

    public function montaRequest(Request $request)
    {
        $retorno = [
            "nome"      => $request->get('nome'),
            "descricao" => $request->get('descricao'),
            "sigla"     => $request->get('sigla'),
            "inativo"   => $request->get('inativo') ?? 1

        ];

        if ($request->idSetor) {
            $retorno['id'] = $request->idSetor;
        }

        return $retorno;
    }
}
