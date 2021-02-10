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

    public $rules = [
        'nome'      => 'required|string|min:5|unique:docs_bpmn,nome',
        'versao'    => 'required',
        'arquivo'   => 'required'
    ];
}
