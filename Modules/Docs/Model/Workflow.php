<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workflow extends Model
{
    use SoftDeletes;

    protected $table = 'docs_workflow';

    protected $fillable = [
        'id',
        'descricao',
        'justificativa',
        'documento_id',
        'etapa_fluxo_id',
        'user_id',
        'documento_revisao',
        'justificativa_lida',
        'tempo_duracao_etapa'
    ];


    public $rules = [
        'descricao' => "required|string",
        'documento_id' => 'required|integer|exists:docs_documento,id',
        'user_id' => 'required|integer|exists:core_users,id',
        'documento_revisao' => "required|string",
    ];


    public function coreUsers()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }

    public function docsEtapaFluxo()
    {
        return $this->hasOne('Modules\Docs\Model\EtapaFluxo', 'id', 'etapa_fluxo_id');
    }

}
