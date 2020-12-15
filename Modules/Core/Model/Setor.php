<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setor extends Model
{
    use SoftDeletes;

    public $table = 'core_setor';

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'sigla',
        'tipo_setor_id'
    ];


    /**
     * Os usuários que pertencem ao setor.
     */
    public function coreUsers()
    {
        return $this->hasMany('Modules\Core\Model\User', 'setor_id', 'id');
    }

}
