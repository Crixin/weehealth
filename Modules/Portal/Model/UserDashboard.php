<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDashboard extends Model
{
    use SoftDeletes;

    public $table = 'portal_user_dashboard';

    protected $fillable = [
        'dashboard_id',
        'user_id'
    ];

    public function coreUser()
    {
        return $this->belongsTo('Modules\Core\Model\User', 'user_id');
    }


    public function portalDashboard()
    {
        return $this->belongsTo('Modules\Portal\Model\Dashboard', 'dashboard_id');
    }
}
