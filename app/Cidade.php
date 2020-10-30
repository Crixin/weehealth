<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    
    public $table = 'cidade';

    protected $fillable = [
        'id', 'nome', 'estado'
    ];

}
