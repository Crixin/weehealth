<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{

    public $table = 'portal_grupo';

    protected $fillable = [
        'id', 'nome', 'descricao'
    ];


    /**
     * Os usuários que pertencem ao grupo.
     */
    public function coreUsers()
    {
        return $this->belongsToMany('Modules\Core\Model\User');
    }


    /**
     * As empresas vinculadas ao grupo.
     */
    public function coreEnterprises()
    {
        return $this->belongsToMany('Modules\Core\Model\Empresa')
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
}
