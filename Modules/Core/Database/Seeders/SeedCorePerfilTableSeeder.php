<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\PerfilRepository;

class SeedCorePerfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perfilRepository = new PerfilRepository();

        $perfilRepository->create([
            'id' => 1,
            'nome' => 'Weecode',
            'permissoes' => '["core_empresa_cad","core_empresa_edit","core_empresa_cons","core_empresa_exc","core_usuario_cad","core_usuario_edit","core_usuario_cons","core_usuario_exc","core_perfil_cad","core_perfil_edit","core_perfil_cons","core_perfil_exc","core_grupo_cad","core_grupo_edit","core_grupo_cons","core_grupo_exc","core_setor_cad","core_setor_edit","core_setor_cons","core_setor_exc","core_config_param","portal_dashboard_cad","portal_dashboard_edit","portal_dashboard_cons","portal_dashboard_exc","portal_dashboard_view","portal_emp_vinc_user","portal_emp_vinc_grupo","portal_emp_vinc_proc","portal_processo_cad","portal_processo_edit","portal_processo_cons","portal_processo_exc","portal_processo_ger","portal_docs_edit_ger","portal_dossie_cad","portal_dossie_cons","portal_tarefa_cad","portal_tarefa_edit","portal_tarefa_cons","portal_tarefa_exc","portal_confg_tarefa_cad","portal_confg_tarefa_edit","portal_confg_tarefa_cons","portal_confg_tarefa_exc","portal_ged_cad","portal_ged_edit","portal_ged_cons","portal_ged_exc","docs_plano_cad","docs_plano_edit","docs_plano_cons","docs_plano_exc","docs_tipo_doc_cad","docs_tipo_doc_edit","docs_tipo_doc_cons","docs_tipo_doc_exc","docs_fluxo_cad","docs_fluxo_edit","docs_fluxo_cons","docs_fluxo_exc","docs_norma_cad","docs_norma_edit","docs_norma_cons","docs_norma_exc","docs_opcao_cad","docs_opcao_edit","docs_opcao_cons","docs_opcao_exc","docs_controle_cad","docs_controle_edit","docs_controle_cons","docs_controle_exc","docs_documento_cad","docs_documento_edit","docs_documento_cons","docs_documento_exc","docs_documento_externo_cad","docs_documento_externo_edit","docs_documento_externo_cons","docs_documento_externo_exc"]'
        ]);
    }
}
