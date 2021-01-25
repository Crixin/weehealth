<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Constants;
use App\Classes\Helper;
use Carbon\Carbon;
use Modules\Portal\Model\{Logs};
use App\Classes\GEDServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Portal\Repositories\EmpresaProcessoRepository;

class RelatorioController extends Controller
{

    private $ged;
    protected $empresaProcessoRepository;

    /*
    * Construtor
    */
    public function __construct(EmpresaProcessoRepository $empresaProcessoRepository)
    {
        $this->ged = new GEDServices(['id_user' => env('ID_GED_USER'), 'server' => env('URL_GED_WSDL')]);
        $this->empresaProcessoRepository = $empresaProcessoRepository;
    }


    public function index($_idEmpresa, $_idProcesso)
    {
        $empresaProcesso = $this->empresaProcessoRepository->findOneBy(
            [
                ['empresa_id', '=', $_idEmpresa],
                ['processo_id', '=', $_idProcesso]
            ]
        );
        $dataInicio      = Carbon::now()->subDays(7)->startOfDay();
        $dataTermino     = Carbon::yesterday()->endOfDay();
        $idxFiltro       = $empresaProcesso->indice_filtro_utilizado;

        $logs = Logs::where('idArea', $empresaProcesso->id_area_ged)->whereBetween('data', [$dataInicio->format('Y-m-d H:i:s'), $dataTermino->format('Y-m-d H:i:s')])->get();
        $logs = is_null($logs) ? collect() : $logs;

        // Limpa a sessão e coloca os valores base para que a funcionalidade 'Listar Documentos' funcione (isso porque essa funcionalidade depende dessas informações de sessão para as restrições dinâmicas de tela)
        if (session()->has('identificadores')) {
            session()->forget('identificadores');
        }

        session(['identificadores' => array(
            'idAreaGED'       => $empresaProcesso->id_area_ged,
            'idxFiltro'       => $empresaProcesso->indice_filtro_utilizado,
            'valorPesquisado' => '-',
            '_idEmpresa'      => $_idEmpresa,
            '_idProcesso'     => $_idProcesso
        )]);

        return view('portal::relatorio.index', [
            'logs' => $logs,
            'filtroAdicional' => null,
            'dataInicio' => $dataInicio->format('Y-m-d'),
            'dataTermino' => $dataTermino->format('Y-m-d'),
            'idAreaGED' => $empresaProcesso->id_area_ged,
            'idEmpresa' => $_idEmpresa,
            'idProcesso' => $_idProcesso,
            'idxFiltro' => $idxFiltro,
            'filtrosDisponiveis' => Constants::$FILTER_OPTIONS[ $idxFiltro ],
        ]);
    }

    public function search(Request $request)
    {
        $dataInicio      = Carbon::parse($request->dataInicio)->startOfDay();
        $dataTermino     = Carbon::parse($request->dataTermino)->endOfDay();
        $idEmpresa       = $request->idEmpresa;
        $idProcesso      = $request->idProcesso;
        $empresaProcesso = $this->empresaProcessoRepository->findOneBy(
            [
                ['empresa_id','=',$idEmpresa],
                ['processo_id','=',$idProcesso]
            ]
        );
        $idxFiltro       = $empresaProcesso->indice_filtro_utilizado;
        $filtroAdicional = $request->filtro_adicional;

        $validator = $this->makeValidator($request);
        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');

            return view('portal::relatorio.index', [
                'logs' => collect(),
                'filtroAdicional' => $filtroAdicional,
                'dataInicio' => $dataInicio->format('Y-m-d'),
                'dataTermino' => $dataTermino->format('Y-m-d'),
                'idAreaGED' => $empresaProcesso->id_area_ged,
                'idEmpresa' => $idEmpresa,
                'idProcesso' => $idProcesso,
                'idxFiltro' => $idxFiltro,
                'filtrosDisponiveis' => Constants::$FILTER_OPTIONS[ $idxFiltro ],
            ]);
        }

        $logs = Logs::where('idArea', $empresaProcesso->id_area_ged)->whereBetween('data', [$dataInicio->format('Y-m-d H:i:s'), $dataTermino->format('Y-m-d H:i:s')]);
        if (is_null($filtroAdicional)) {
            $logs = $logs->get();
        } else {
            session()->put('identificadores.valorPesquisado', $filtroAdicional);

            $valor = preg_replace( '/[^0-9]/', '', $filtroAdicional ); // Os filtros possíveis são CPF e Matrícula, então eu limpo o valor digitado para que contenha apenas números
            $logs  = $logs->where('valor', 'ILIKE', '%' . $valor . '%')->get();
        }
        $logs = is_null($logs) ? collect() : $logs;

        return view('portal::relatorio.index', [
            'logs' => $logs,
            'filtroAdicional' => $filtroAdicional,
            'dataInicio' => $dataInicio->format('Y-m-d'),
            'dataTermino' => $dataTermino->format('Y-m-d'),
            'idAreaGED' => $empresaProcesso->id_area_ged,
            'idEmpresa' => $idEmpresa,
            'idProcesso' => $idProcesso,
            'idxFiltro' => $idxFiltro,
            'filtrosDisponiveis' => Constants::$FILTER_OPTIONS[ $idxFiltro ],
        ]);
    }

    private function makeValidator(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'dataInicio'  => 'required|after_or_equal:-3 months -1 day',
                'dataTermino' => 'required|before:today|after_or_equal:dataInicio',
            ],
            [
                'dataInicio.after_or_equal' => 'O campo Data Início deve conter uma data igual ou superior a ' . date("d/m/Y", strtotime("-3 months")) . '.',
                'dataTermino.before' => 'O campo Data Término deve conter uma data anterior a ' . date("d/m/Y", strtotime("now")) . '.',
                'dataTermino.after_or_equal' => 'O campo Data Término deve conter uma data superior ou igual ao campo Data Início.',
            ]
        );
        return $validator;
    }
}
