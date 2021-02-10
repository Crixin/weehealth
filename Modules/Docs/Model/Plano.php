<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plano extends Model
{
    use SoftDeletes;

    protected $fillable =
    [
        'id',
        'nome',
        'ativo'
    ];

    protected $table = "docs_plano";

    public $rules = [
        'nome'      => 'required|string|min:5|unique:docs_plano,nome',
        'status'    => 'required'
    ];
}
