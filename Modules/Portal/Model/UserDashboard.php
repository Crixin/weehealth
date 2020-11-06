<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class UserDashboard extends Model
{
    public $table = 'portal_php user_dashboard';

    protected $fillable = [
        'dashboard_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('Modules\Core\Model\User');
    }


    public function dashboard()
    {
        return $this->belongsTo('Modules\Portal\Model\Dashboard');
    }
}
