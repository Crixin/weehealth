<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bpmn extends Model
{
    use SoftDeletes;

    protected $table = "docs_bpmn";

    protected $fillable = [
        'id',
        'nome',
        'versao',
        'arquivo'
    ];

}
