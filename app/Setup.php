<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    
    public $table = 'setup';

    protected $fillable = [
        'id', 'logo_login', 'logo_sistema'
    ];
}
