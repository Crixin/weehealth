<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaProcessoGrupo extends Model
{
    public $table = "portal_empresa_processo_grupo";

    protected $fillable = [
        'id',
        'grupo_id',
        'empresa_processo_id',
        'filtros',
    ];


    public function grupo()
    {
        return $this->belongsTo('Modules\Portal\Model\Grupo');
    }

    
    public function empresaProcesso()
    {
        return $this->belongsTo('Modules\Portal\Model\EmpresaProcesso');
    }
}
