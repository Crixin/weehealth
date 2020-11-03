<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaGrupo extends Model
{
    
    public $table = 'empresa_grupo';

    protected $fillable = [
        'id', 'permissao_download', 'permissao_visualizar', 'permissao_impressao', 'permissao_aprovar_doc', 'permissao_excluir_doc', 'permissao_upload_doc', 'permissao_receber_email', 'empresa_id', 'grupo_id', 'permissao_editar'
    ];
}
