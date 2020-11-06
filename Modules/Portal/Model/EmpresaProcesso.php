<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaProcesso extends Model
{
    
    public $table = 'portal_empresa_processo';

    protected $fillable = [
        'id', 'indice_filtro_utilizado', 'id_area_ged', 'empresa_id', 'processo_id', 'todos_filtros_pesquisaveis'
    ];


    public function processo()
    {
        return $this->hasOne('Modules\Portal\Model\Processo', 'id', 'processo_id');
    }


    public function empresa()
    {
        return $this->hasOne('Modules\Core\Model\Empresa', 'id', 'empresa_id');
    }


    public function empresaProcessoGrupo()
    {
        return $this->hasMany('Modules\Portal\Model\EmpresaProcessoGrupo');
    }
}
