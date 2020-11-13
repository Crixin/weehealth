<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public $table = 'portal_dashboard';

    protected $fillable = [
        'id', 'nome', 'config'
    ];
}
