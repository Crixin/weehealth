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
        'tipo_setor_id',
        'inativo'
    ];

    public $rules = [
        'nome'      => 'required|string|min:5|unique:core_setor,nome',
        'sigla'     => 'required|string|max:5',
        'descricao' => 'required|string|min:5'
    ];

    /**
     * Os usuários que pertencem ao setor.
     */
    public function coreUsers()
    {
        return $this->hasMany('Modules\Core\Model\User', 'setor_id', 'id');
    }

    public function docsDocumento()
    {
        return $this->hasMany('Modules\Docs\Model\Documento', 'setor_id', 'id');
    }

}
