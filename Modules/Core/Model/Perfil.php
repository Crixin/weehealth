<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    public $table = 'core_perfil';

    protected $fillable = [
        'id',
        'nome'
    ];


   


    public function coreUsers()
    {
        return $this->hasMany('Modules\Core\Model\User');
    }
}
