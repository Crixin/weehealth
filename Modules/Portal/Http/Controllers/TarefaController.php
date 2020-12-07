<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{EmpresaRepository,GrupoUserRepository};
use Modules\Portal\Repositories\{
    TarefaRepository,
    ConfiguracaoTarefaRepository,
    EmpresaGrupoRepository,
    EmpresaUserRepository,
    EmpresaProcessoRepository
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    protected $tarefaRepository;
    protected $configuracaoTarefa;
    protected $usuarioRepository;
    protected $grupoUserRepository;
    protected $empresaGrupoRepository;
    protected $empresaUserRepository;
    protected $empresaRepository;
    protected $empresaProcessoRepository;

    public function __construct(
        TarefaRepository $tarefa,
        ConfiguracaoTarefaRepository $configuracao,
        GrupoUserRepository $grupoUser,
        EmpresaGrupoRepository $empresaGrupo,
        EmpresaUserRepository $empresaUserGrupo,
        EmpresaRepository $empresa,
        EmpresaProcessoRepository $empresaProcesso
    )
    {
        $this->tarefaRepository = $tarefa;
        $this->configuracaoTarefa = $configuracao;

        $this->grupoUserRepository = $grupoUser;
        $this->empresaGrupoRepository = $empresaGrupo;
        $this->empresaUserRepository = $empresaUserGrupo;
        $this->empresaRepository = $empresa;
        $this->empresaProcessoRepository = $empresaProcesso;
    }

    public function index()
    {
        $tarefas = $this->tarefaRepository->findAll();
        return view('portal::tarefa.index', compact('tarefas'));
    }

    public function newTarefa()
    {
        $empresas = Helper::getProcessesByUserAccess();
        $configuracoes = $this->configuracaoTarefa->findAll();
        return view('portal::tarefa.create', compact('configuracoes', 'empresas'));
    }

    public function saveTarefa(Request $request)
    {
        $validador = self::validador($request);
        if ($validador == 1) {
            return redirect()->back()->withInput();
        }
        $montaCreate = self::montaRequest($request);
        DB::beginTransaction();
        try {
            $this->tarefaRepository->create($montaCreate);
            DB::commit();
            Helper::setNotify('Nova tarefa criada com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao gravar o registro', 'danger|close-circle');
        }
        return redirect()->route('portal.tarefa');
    }

    public function editTarefa($_id)
    {
        $tarefa = $this->tarefaRepository->find($_id);
        $empresas = Helper::getProcessesByUserAccess();
        $configuracoes = $this->configuracaoTarefa->findAll();

        $decode = json_decode($tarefa->indices);
        $indices = $decode->indice;
        return view('portal::tarefa.update', compact('tarefa', 'empresas', 'configuracoes', 'indices'));
    }

    public function updateTarefa(Request $request)
    {
        $validador = self::validador($request);
        if ($validador == 1) {
            return redirect()->back()->withInput();
        }
        $montaUpdate = self::montaRequest($request);
        DB::beginTransaction();
        try {
            $this->tarefaRepository->update($montaUpdate, $request->get('idTarefa'));
            DB::commit();
            Helper::setNotify('Informações da tarefa alteradas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao alterar o registro', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    public function deleteTarefa(Request $request)
    {
        $id = $request = $request->id;
        DB::beginTransaction();
        try {
            $this->tarefaRepository->delete($id);
            DB::commit();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            DB::rollback();
            return response()->json(['response' => 'erro']);
        }
    }

    private function validador(Request $_request)
    {
        $erro = 0;
        $campos = [
            'configuracao' => 'required|numeric',
            'pasta' => 'required|string',
            'frequencia' => 'required|string',
            'identificador' => 'required|string',
            'area' => 'required|string',
            'tipo_indexacao' => 'required|string',
            'indices' => 'required',
            'pastaRejeitados' => 'required|string',
            'hora' => ''
        ];

        $campos['hora'] = $_request->frequencia == "dailyAt" ? 'required|date_format:H:i' : '';

        $validator = Validator::make($_request->all(), $campos);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            $erro = 1;
        }
        return $erro;
    }

    private function montaRequest(Request $request)
    {
        return [
            'configuracao_id'   => $request->get('configuracao'),
            'pasta' => $request->get('pasta'),
            'frequencia' => $request->get('frequencia') ?? '',
            'identificador' => $request->get('identificador') ?? '',
            'area' => $request->get('area') ?? '',
            'tipo_indexacao' => $request->get('tipo_indexacao') ?? '',
            'indices' => $request->get('indices') ?? '',
            'pasta_rejeitados' => $request->pastaRejeitados,
            'hora' => $request->hora ?? null
        ];
    }
}
