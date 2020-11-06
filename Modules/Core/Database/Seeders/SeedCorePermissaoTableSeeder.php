<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\PerfilPermissaoRepository;
use Modules\Core\Repositories\PermissaoRepository;

class SeedCorePermissaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissaoRepository = new PermissaoRepository();
        $perfilPermissao = new PerfilPermissaoRepository();

        $permissoes = [
            'mod_base' => "Módulo Base (Empresa, Grupos, Usuários e Processos)",
            'mod_dashboard' => "Módulo de Dashboards",
            'mod_relatorios' => "Módulo de Relatório",
            'mod_dossie' => "Módulo de Dossiê",
            'conf_setup' => "Configuração da Setup",
            //'conf_parametros' => "Configuração dos Parametros ",
            //'conf_administradores' => "Configuração de Administradores",
            //'mod_processos' => "Módulo de Processos",
            'mod_tarefas' => "Módulo de Agendamento de Tarefas",
            'view_dashboard' => "Visualização de Dashboards",
            'ger_processos' => "Gerenciamento de Processos",
        ];

        foreach ($permissoes as $nome => $descricao) {
            $retorno = $permissaoRepository->create([
                "nome" => $nome,
                "descricao" => $descricao
            ]);

            $perfilPermissao->create([
                'perfil_id' => 1,
                'permissao_id' => $retorno->id
            ]);
        }
    }
}
