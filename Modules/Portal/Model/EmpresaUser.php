<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaUser extends Model
{
    public $table = 'portal_empresa_user';

    protected $fillable = [
        'id',
        'permissao_download',
        'permissao_visualizar',
        'permissao_impressao',
        'permissao_aprovar_doc',
        'permissao_excluir_doc',
        'permissao_upload_doc',
        'permissao_receber_email',
        'empresa_id',
        'user_id',
        'permissao_editar'
    ];

    public function coreUser()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }

    public function coreEmpresa()
    {
        return $this->hasOne('Modules\Core\Model\Empresa', 'id', 'empresa_id');
    }
}
