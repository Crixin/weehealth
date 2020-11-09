<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Modules\Portal\Repositories\ConfiguracaoTarefaRepository;
use Illuminate\Http\Request;

class ConfiguracaoTarefaController extends Controller
{
    protected $configuracaoTarefaRepository;

    public function __construct(ConfiguracaoTarefaRepository $configTarefa)
    {
        $this->configuracaoTarefaRepository = $configTarefa;
    }

    public function index()
    {
        $configuracoes = $this->configuracaoTarefaRepository->findAll();
        return view('portal::configuracaoTarefa.index', compact('configuracoes'));
    }

    public function newConfiguracaoTarefa()
    {
        return view('portal::configuracaoTarefa.create');
    }

    public function saveConfiguracaoTarefa(Request $request)
    {
        $validador = self::validador($request);
        if ($validador == 1) {
            return redirect()->back()->withInput();
        }
        $montaCreate = self::montaRequest($request);
        DB::beginTransaction();
        try {
            $this->configuracaoTarefaRepository->create($montaCreate);
            DB::commit();
            Helper::setNotify('Nova configuração criada com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao gravar o registro', 'danger|close-circle');
        }
        return redirect()->route('config-tarefa');
    }

    public function editConfiguracaoTarefa($_id)
    {
        $configuracao = $this->configuracaoTarefaRepository->find($_id);
        return view('portal::configuracaoTarefa.update', compact('configuracao'));
    }

    public function updateConfiguracaoTarefa(Request $request)
    {
        $validador = self::validador($request);
        if ($validador == 1) {
            return redirect()->back()->withInput();
        }
        $montaUpdate = self::montaRequest($request);
        DB::beginTransaction();
        try {
            $this->configuracaoTarefaRepository->update($montaUpdate, $request->get('idConfiguracao'));
            DB::commit();
            Helper::setNotify('Informações da configuração alteradas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao alterar o registro', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    public function deleteConfiguracaoTarefa(Request $request)
    {
        $id = $request = $request->id;
        DB::beginTransaction();
        try {
            $this->configuracaoTarefaRepository->delete($id);
            DB::commit();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            DB::rollback();
            return response()->json(['response' => 'erro']);
        }
    }

    private function validador(Request $request)
    {
        $erro = 0;
        $tipo = $request->tipoConfiguracao;

        if ($tipo == 'FTP') {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'tipoConfiguracao' => 'required|string',
                'ip' => 'required|string',
                'porta' => 'required|numeric',
                'usuario' => 'required|string',
                'senha' => 'required|string',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'tipoConfiguracao' => 'required|string',
                'pastaSistema' => 'required|string'
            ]);
        }

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            $erro = 1;
        }
        return $erro;
    }

    private function montaRequest(Request $request)
    {
        $monta = [
            'nome'   => $request->get('nome'),
            'tipo' => $request->get('tipoConfiguracao'),
            'caminho' => $request->get('pastaSistema') ?? '',
            'ip' => $request->get('ip') ?? '',
            'porta' => $request->get('porta') ?? 0,
            'usuario' => $request->get('usuario') ?? '',
            'senha' => $request->get('senha') ?? '',
         ];
         return $monta;
    }
}
