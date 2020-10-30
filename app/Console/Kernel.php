<?php

namespace App\Console;

use App\Repositories\TarefaRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $_schedule)
    {
        /*
        * Rotina que verifica a necessidade de execução das tarefas agendadas
        */
        $tarefaRepository = new TarefaRepository();

        $tarefas = $tarefaRepository->findAll();

        foreach ($tarefas as $tarefa) {
            $this->timeForTask($_schedule, $tarefa->frequencia, 'command:tarefa ' . $tarefa->id, $tarefa->hora ?? "");
        }

        /**
        *  Itera sempre sob todos os logs gerados a partir de ações realizadas em documentos no dia anterior.
        *   À cada iteração, a tabela de logs é populada para que a consulta na tela de logs se torne mais rápida.
        */
        //$schedule->command('command:log')->dailyAt('22:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }


    private function timeForTask(Schedule $_schedule, string $_frequencia = "", string $_command = "", string $_time = "")
    {
        switch ($_frequencia) {
            case '1m':
                return $_schedule->command($_command)->everyMinute();
                break;
            case '2m':
                return $_schedule->command($_command)->everyTwoMinutes();
                break;
            case '3m':
                return $_schedule->command($_command)->everyThreeMinutes();
                break;
            case '4m':
                return $_schedule->command($_command)->everyFourMinutes();
                break;
            case '5m':
                return $_schedule->command($_command)->everyFiveMinutes();
                break;
            case '10m':
                return $_schedule->command($_command)->everyTenMinutes();
                break;
            case '15m':
                return $_schedule->command($_command)->everyFifteenMinutes();
                break;
            case '30m':
                return $_schedule->command($_command)->everyThirtyMinutes();
                break;
            case '1h':
                return $_schedule->command($_command)->hourly();
                break;
            case '2h':
                return $_schedule->command($_command)->everyTwoHours();
                break;
            case '3h':
                return $_schedule->command($_command)->everyThreeHours();
                break;
            case '4h':
                return $_schedule->command($_command)->everyFourHours();
                break;
            case '6h':
                return $_schedule->command($_command)->everySixHours();
                break;
            case 'dailyAt':
                return $_schedule->command($_command)->dailyAt($_time);
                break;
            default:
                return $_schedule->command($_command)->hourly();
                break;
        }
    }
}
