<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaGrupo extends Model
{
    use SoftDeletes;

    public $table = 'portal_empresa_grupo';

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
        'grupo_id',
        'permissao_editar'
    ];

    public function coreEmpresa()
    {
        return $this->hasOne('Modules\Core\Model\Empresa', 'id', 'empresa_id');
    }

    public function coreGrupo()
    {
        return $this->hasOne('Modules\Core\Model\Grupo', 'id', 'grupo_id');
    }
}
