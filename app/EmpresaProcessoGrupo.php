<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpresaProcessoGrupo extends Model
{
    public $table = "empresa_processo_grupo";

    protected $fillable = [
        'id',
        'grupo_id',
        'empresa_processo_id',
        'filtros',
    ];


    public function grupo()
    {
        return $this->belongsTo('App\Grupo');
    }

    
    public function empresaProcesso()
    {
        return $this->belongsTo('App\EmpresaProcesso');
    }
}
