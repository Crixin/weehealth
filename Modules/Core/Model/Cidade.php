<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    public $table = 'core_cidade';

    protected $fillable = [
        'id', 'nome', 'estado'
    ];
}
