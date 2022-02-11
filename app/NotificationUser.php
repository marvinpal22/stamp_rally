<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationUser extends Pivot
{
    use SoftDeletes;
    protected $fillable = [
        'device_token'
    ];

    public function Notifications(){
        return $this->belongsTo('App\Notification', 'notification_id', 'id');
    }

}
