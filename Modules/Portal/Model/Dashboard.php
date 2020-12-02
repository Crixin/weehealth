<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dashboard extends Model
{
    use SoftDeletes;

    public $table = 'portal_dashboard';

    protected $fillable = [
        'id', 'nome', 'config'
    ];
}
