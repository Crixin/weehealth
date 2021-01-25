<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\{GrupoRepository, SetorRepository};
use Modules\Core\Services\{GrupoUserService, GrupoService};

class GrupoController extends Controller
{
    protected $grupoRepository;
    private $setorRepository;
    private $grupoUserService;
    private $grupoService;

    public function __construct(
        GrupoRepository $grupoRepository,
        SetorRepository $setorRepository,
        GrupoUserService $grupoUserService,
        GrupoService $grupoService
    ) {
        $this->grupoRepository = $grupoRepository;
        $this->setorRepository = $setorRepository;
        $this->grupoUserService = $grupoUserService;
        $this->grupoService = $grupoService;
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
        $cadastro = self::montaRequest($request);
        try {
            DB::transaction(function () use ($cadastro, $request) {
                $grupo = $this->grupoRepository->create($cadastro);
            });
            Helper::setNotify('Grupo criado com sucesso!', 'success|check-circle');
            return redirect()->route('core.grupo');
        } catch (\Throwable $th) {
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
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
        $grupo = $this->grupoRepository->find($_id, ['coreUsers']);
        $setores = $this->setorRepository->findAll(['coreUsers', 'coreUsers.corePerfil']);
        return view('core::grupo.usuarios_vinculados', compact('grupo', 'setores'));
    }


    public function updateLinkedUsers(Request $request)
    {
        $data['grupo_id'] = $request->idGrupo;
        $data['user_id'] = $request->usuarios_grupo;
        
        $grupo = $this->grupoRepository->find($data['grupo_id']);

        $reponse = $this->grupoUserService->store($data);

        if (is_object($reponse) && get_class($reponse) === "Illuminate\Http\RedirectResponse") {
            return $reponse;
        }

        if ($reponse) {
            return redirect()->back();
        }

        return redirect()->back()->withInput();
    }

    public function validador(Request $request)
    {
        $validator = Validator::make
        (
            $request->all(),
            [
                'nome'      => empty($request->get('idGrupo')) ? 'required|string|max:100|unique:core_grupo,nome' : 'required|string|max:100|unique:core_grupo,nome,' . $request->idGrupo,
                'descricao' => 'required|string|max:300'
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
            "nome" => $request->get('nome'),
            "descricao" => $request->get('descricao'),
            "sigla" => (strlen($request->get('nome')) >= 3) ? strtoupper(substr($request->get('nome'), 0, 3)) : "SIGLA"
        ];
    }


    public function destroy(request $request)
    {
        return $this->grupoService->delete($request->grupo_id);
    }
}
