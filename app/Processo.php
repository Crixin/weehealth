<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    
    public $table = 'processo';

    protected $fillable = [
        'id', 'nome', 'descricao'
    ];


    /**
     * As empresas que utilizam este processo (área do GED).
     * 
     * PS: usa-se o `withPivot` quando uma tabela de relacionamento possui valores adicionais, ou seja, não apenas as keys de vinculação
     */
    public function enterprises() {
        return $this->belongsToMany('App\Empresa')->withPivot('id_area_ged', 'empresa_id', 'processo_id');
    }

}
