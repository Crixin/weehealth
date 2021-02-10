<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoUser extends Model
{
    use SoftDeletes;

    public $table = 'core_grupo_user';

    protected $fillable = [
        'id',
        'grupo_id',
        'user_id'
    ];

    public $rules = [
        'grupo_id' => 'required|integer|exists:core_grupo,id',
        'user_id' => 'required|integer|exists:core_users,id'
    ];

    public function coreUsers()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }


    public function coreGrupo()
    {
        return $this->hasOne('Modules\Core\Model\Grupo', 'id', 'grupo_id');
    }
}
