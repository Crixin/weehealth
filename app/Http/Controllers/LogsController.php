<?php

namespace App\Http\Controllers;

use App\Logs;
use Carbon\Carbon;
use App\Classes\{GEDServices, Helper};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class LogsController extends Controller
{
    /**
     * Variável responsável por gerenciar a interação com os serviços do GED.
     *
     * @var GEDServices
     */
    private $ged;

    /*
    * Construtor
    */
    public function __construct()
    {
        $this->ged = new GEDServices(['id_user' => env('ID_GED_USER'), 'server' => env('URL_GED_WSDL')]);
    }



    public function index()
    {
        $startDate = Carbon::now()->subDays(7)->startOfDay();
        $endDate   = Carbon::yesterday()->endOfDay();
        
        $logs = Logs::whereBetween('data', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])->get();
        $logs = is_null($logs) ? collect() : $logs;
        
        return view('logs.index', [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'logs' => $logs,
        ]);
    }


    public function search(Request $request)
    {
        $startDate = Carbon::parse($request->dataInicio)->startOfDay();
        $endDate   = Carbon::parse($request->dataTermino)->endOfDay();
        $validator = $this->makeValidator($request);
        $logs      = collect();
        
        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            
            return view('logs.index', [
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d'),
                'logs' => $logs,
            ]);
        }
        
        // Captura todos os logs que foram criados dentro do período definido pelo usuário
        $logs = Logs::whereBetween('data', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])->get();
        
        return view('logs.index', [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'logs' => $logs,
        ]);
    }



    private function makeValidator(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
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
