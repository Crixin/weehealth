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
        return $this->hasOne('App\Processo', 'id', 'processo_id');
    }


    public function empresa()
    {
        return $this->hasOne('App\Empresa', 'id', 'empresa_id');
    }


    public function empresaProcessoGrupo()
    {
        return $this->hasMany('App\EmpresaProcessoGrupo');
    }
}
