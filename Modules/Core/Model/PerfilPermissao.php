<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class PerfilPermissao extends Model
{
    public $table = 'core_perfil_permissao';

    protected $fillable = [
        'id',
        'perfil_id',
        'permissao_id'
    ];
}
