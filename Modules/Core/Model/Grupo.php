<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model
{
    use SoftDeletes;

    public $table = 'core_grupo';

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'sigla'
    ];


    /**
     * Os usuários que pertencem ao grupo.
     */
    public function coreUsers()
    {
        return $this->belongsToMany('Modules\Core\Model\User', 'Modules\Core\Model\GrupoUser')->whereNull('core_grupo_user.deleted_at');
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
