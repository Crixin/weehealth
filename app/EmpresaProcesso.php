<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpresaProcesso extends Model
{
    
    public $table = 'empresa_processo';

    protected $fillable = [
        'id', 'indice_filtro_utilizado', 'id_area_ged', 'empresa_id', 'processo_id', 'todos_filtros_pesquisaveis'
    ];


    /**
     * Este método dá um "apelido" para a coluna 'indice_filtro_utilizado' e, a partir disso, esse valor pode ser usado em qualquer lugar como 'filtro'
     *
     * https://laravel.com/docs/5.5/eloquent-mutators#accessors-and-mutators
     */
    public function getFiltroAttribute()
    {
        return $this->attributes['indice_filtro_utilizado'];
    }


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
