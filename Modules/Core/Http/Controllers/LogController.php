<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{LogRepository, UserRepository};

class LogController extends Controller
{

    protected $usuarioRepository;
    protected $logRepository;

    public function __construct(LogRepository $logRepository, UserRepository $usuarioRepository)
    {
        $this->logRepository = $logRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $this->criaTrigger();
        /*
        $buscaUsuario = $this->usuarioRepository->findAll();
        foreach ($buscaUsuario as $key => $value) {
            $usuarios[$value->name] = $value->name;
        }
        $operacoes = ["INSERT" => "INSERT", "UPDATE" => "UPDATE", "DELETE" => "DELETE"];
        $tipoData = ["hoje" => "Hoje", "mes" => "Mês", "mespassado" => "Mês Passado", "definir" => "Definir Período"];
        $buscaTabelas = DB::select("SELECT * from pg_tables where schemaname = '" . env('DB_SCHEMA') . "' order by pg_tables ASC");
        $tabelas = [];
        foreach ($buscaTabelas as $key => $value) {
            $tabelas[$value->tablename] = $value->tablename;
        }

        $colunas = [];
        if ($request->tabela) {
            $buscaColuna = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ?', [$request->tabela]);
            foreach ($buscaColuna as $key => $value) {
                $colunas[$value->column_name] = $value->column_name;
            }
        }
        $where = [];
        if ($request->usuario) {
            foreach ($request->usuario as $key => $value) {
                array_push($where, ['usuario','like','%' . strtoupper($value) . '%', 'OR']);
            }
        }

        if ($request->chave) {
            array_push($where, ['chave','=',$request->chave]);
        }

        if ($request->operacao) {
            array_push($where, ['operacao','',$request->operacao, "IN"]);
        }

        if ($request->tabela) {
            array_push($where, ['tabela','=',$request->tabela]);
        }

        if ($request->coluna) {
            array_push($where, ['coluna','=',$request->coluna]);
        }
 
        if ($request->tipoData) {
            switch ($request->tipoData) {
                case 'hoje':
                    array_push($where, ['created_at','>=',date('Y-m-d' . ' 00:00:01'), "AND"]);
                    array_push($where, ['created_at','<=',date('Y-m-d' . ' 23:59:59')]);
                    break;
                case 'mes':
                    $datas = Helper::mesBetween(0);
                    array_push($where, ['created_at','>=',$datas['dataInicial'] . ' 00:00:01', "AND"]);
                    array_push($where, ['created_at','<=',$datas['dataFinal'] . ' 23:59:59']);
                    break;
                case 'mespassado':
                    $datas = Helper::mesPassadoBetween();
                    array_push($where, ['created_at','>=',$datas['dataInicial'] . ' 00:00:01', "AND"]);
                    array_push($where, ['created_at','<=',$datas['dataFinal'] . ' 23:59:59']);
                    break;
                case 'definir':
                    array_push($where, ['created_at','>=',$request->dataInicial, "AND"]);
                    array_push($where, ['created_at','<=',$request->dataFinal]);
                    break;
            }
        }
        
        if ($request->getMethod() == 'POST') {
            $logs = $this->logRepository->findBy($where);
        } else {
            $logs = [];
        }
        //SELECIONADO
        $usuarioSelecionado = $request->usuario;
        $chaveSelecionado = $request->chave;
        $operacaoSelecionado = $request->operacao;
        $opcoesSelecionado = $request->tipoData;
        $dataInicialSelecionado = $request->dataInicial;
        $dataFinalSelecionado = $request->dataFinal;
        $tabelaSelecionada = $request->tabela;
        $colunaSelecionada = $request->coluna;

        return view('core::log.index',
            compact(
                'logs',
                'usuarios',
                'operacoes',
                'tipoData',
                'tabelas',
                'colunas',
                'usuarioSelecionado',
                'chaveSelecionado',
                'operacaoSelecionado',
                'opcoesSelecionado',
                'dataInicialSelecionado',
                'dataFinalSelecionado',
                'tabelaSelecionada',
                'colunaSelecionada'
            )
        );
        */
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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
        return view('core::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function buscaCamposTabela(Request $request)
    {
        try {
            $campos = DB::transaction(function () use ($request) {
                $tabela = $request->tabela;
                return DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ?', [$tabela]);
            });
            return response()->json(['response' => 'sucesso', 'data' => $campos]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function criaTrigger()
    {
        ini_set('memory_limit', '3000M');
        ini_set('max_execution_time', '0');

        $criaTrigger = DB::select("select geracao_inicial_triggers('public')");
        $criacaoLote = DB::select($criaTrigger[0]->geracao_inicial_triggers);
        $executaLote = DB::select("select criar_triggers_lote('public')");
        DB::unprepared($executaLote[0]->criar_triggers_lote);
    }
}
