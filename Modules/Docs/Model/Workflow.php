<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $table = 'docs_workflow';

    protected $fillable = [
        'id',
        'etapa_num',
        'etapa',
        'descricao',
        'justificativa',
        'documento_id'
    ];

}
