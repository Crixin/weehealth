<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class GrupoUser extends Model
{
    public $table = 'portal_grupo_user';

    protected $fillable = [
        'id', 'grupo_id', 'user_id'
    ];

    public function coreUser()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }

    public function portalGrupo()
    {
        return $this->hasOne('Modules\Portal\Model\Empresa', 'id', 'empresa_id');
    }
}
