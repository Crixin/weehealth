<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    public $table = 'core_empresa';

    protected $fillable = [
        'id', 'nome', 'cnpj', 'telefone', 'responsavel_contato', 'pasta_ftp', 'obs', 'cidade_id'
    ];



    /**
     * Os grupos que pertencem à empresa.
     *
     * PS: usa-se o `withPivot` quando uma tabela de relacionamento possui valores adicionais, ou seja, não apenas as keys de vinculação
     */
    public function portalGroups()
    {
        return $this->belongsToMany('Modules\Portal\Model\Grupo', 'Modules\Portal\Model\EmpresaGrupo')
        ->withPivot(
            'permissao_download',
            'permissao_visualizar',
            'permissao_impressao',
            'permissao_aprovar_doc',
            'permissao_excluir_doc',
            'permissao_upload_doc',
            'permissao_receber_email',
            'empresa_id',
            'grupo_id'
        );
    }


    /**
     * Os usuários que pertencem à empresa.
     *
     * PS: usa-se o `withPivot` quando uma tabela de relacionamento possui valores adicionais, ou seja, não apenas as keys de vinculação
     */
    public function coreUsers()
    {
        return $this->belongsToMany('Modules\Core\Model\User', 'Modules\Portal\Model\EmpresaUser')
        ->withPivot(
            'permissao_download',
            'permissao_visualizar',
            'permissao_impressao',
            'permissao_aprovar_doc',
            'permissao_excluir_doc',
            'permissao_upload_doc',
            'permissao_receber_email',
            'empresa_id',
            'user_id'
        );
    }


    /**
     * Os processos (áreas) do GED utilizados pela empresa.
     *
     * PS: usa-se o `withPivot` quando uma tabela de relacionamento possui valores adicionais, ou seja, não apenas as keys de vinculação
     */
    public function portalProcesses()
    {
        return $this->belongsToMany('Modules\Portal\Model\Processo', 'Modules\Portal\Model\EmpresaProcesso')->withPivot(
            'id',
            'id_area_ged',
            'empresa_id',
            'processo_id',
            'indice_filtro_utilizado',
            'todos_filtros_pesquisaveis'
        );
    }
}
