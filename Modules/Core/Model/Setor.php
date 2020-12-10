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
     * Os usuários que pertencem ao grupo.
     */
    public function coreUsers()
    {
        return $this->belongsToMany('Modules\Core\Model\User', 'Modules\Core\Model\Setor');
    }

}
