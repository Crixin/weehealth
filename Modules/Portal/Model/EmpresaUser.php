<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaUser extends Model
{
    public $table = 'empresa_user';

    protected $fillable = [
        'id', 'permissao_download', 'permissao_visualizar', 'permissao_impressao', 'permissao_aprovar_doc', 'permissao_excluir_doc', 'permissao_upload_doc', 'permissao_receber_email', 'empresa_id', 'user_id', 'permissao_editar'
    ];
}
