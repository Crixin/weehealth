<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidade extends Model
{
    use SoftDeletes;

    public $table = 'core_cidade';

    protected $fillable = [
        'id',
        'nome',
        'estado'
    ];
}
