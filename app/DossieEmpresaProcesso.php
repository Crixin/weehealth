<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DossieEmpresaProcesso extends Model
{
    public $table = 'dossie_empresa_processo';

    protected $fillable = [
        'id',
        'dossie_id',
        'empresa_processo_id'
    ];


    public function dossie()
    {
        return $this->belongsTo('App\Dossie');
    }


    public function empresaProcesso()
    {
        return $this->belongsTo('App\EmpresaProcesso');
    }

}
