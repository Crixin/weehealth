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
        'versao_documento'
    ];
}
