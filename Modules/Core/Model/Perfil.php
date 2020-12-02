<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model
{
    use SoftDeletes;
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
