<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public $table = 'dashboard';

    protected $fillable = [
        'id', 'nome', 'config'
    ];
}