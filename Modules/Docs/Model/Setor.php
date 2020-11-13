<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    
    protected $table = 'docs_setor';

    protected $fillable = [
        'id',
        'nome',
        'sigla',
        'descricao',
        'tipo_setor_id'
    ];

    public function getNomeSiglaAttribute() {
        return $this->nome . ';' . $this->sigla;  
    }

}
