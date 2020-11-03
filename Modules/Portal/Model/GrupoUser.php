<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class GrupoUser extends Model
{
    
    public $table = 'grupo_user';
    
    protected $fillable = [
        'id', 'grupo_id', 'user_id'
    ];
}
