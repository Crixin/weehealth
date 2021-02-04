<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AtribuirPermissaoTabela extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:permissao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atribui permissão em tabelas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '3000M');
        ini_set('max_execution_time', '0');

        DB::purge('pgsql');
        Config::set('database.connections.pgsql.username', 'postgres');
        Config::set('database.connections.pgsql.password', 'admin');
        DB::reconnect('pgsql');

        $criaTrigger = DB::select("select geracao_inicial_triggers('public')");
        $criacaoLote = DB::select($criaTrigger[0]->geracao_inicial_triggers);
        $executaLote = DB::select("select criar_triggers_lote('public')");
        DB::unprepared($executaLote[0]->criar_triggers_lote);

        DB::unprepared('grant SELECT, INSERT, UPDATE, DELETE on all tables in schema public to weehealth');
        DB::unprepared('grant ALL on all sequences in schema public to weehealth');
    }
}
