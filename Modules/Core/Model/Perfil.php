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
        'nome',
        'permissoes'
    ];

    
    protected $casts = [
        'permissoes' => 'array'
    ];


    public $rules = [
        'nome' => 'required|string|unique:core_perfil,nome'
    ];


    public function coreUsers()
    {
        return $this->hasMany('Modules\Core\Model\User');
    }
}
