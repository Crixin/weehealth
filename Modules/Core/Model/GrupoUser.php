<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class GrupoUser extends Model
{
    public $table = 'core_grupo_user';

    protected $fillable = [
        'id',
        'grupo_id',
        'user_id'
    ];

    public function coreUser()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }

    public function coreGrupo()
    {
        return $this->hasOne('Modules\Core\Model\Empresa', 'id', 'empresa_id');
    }
}
