<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public $table = 'dashboard';

    protected $fillable = [
        'id', 'nome', 'config'
    ];
}
