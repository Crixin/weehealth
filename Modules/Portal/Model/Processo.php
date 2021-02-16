<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processo extends Model
{
    use SoftDeletes;

    public $table = 'portal_processo';

    protected $fillable = [
        'id', 'nome', 'descricao'
    ];


    /**
     * As empresas que utilizam este processo (área do GED).
     *
     * PS: usa-se o `withPivot` quando uma tabela de relacionamento possui valores adicionais, ou seja, não apenas as keys de vinculação
     */
    public function coreEnterprises()
    {
        return $this->belongsToMany('Modules\Core\Model\Empresa')->withPivot('id_area_ged', 'empresa_id', 'processo_id');
    }
}
