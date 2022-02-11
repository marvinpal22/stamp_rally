<?php

namespace App;

use App\Post;
use App\Entry;
use App\Store;
use App\NotificationUser;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable  implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		 'password', 'role' , 'full_name', 'username', 'is_submit',
		'email', 'contact_no', 'address', 'impressions'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



	//get scanned stores\
    public function stores(){
		return $this->belongsToMany(Store::class, 'entries');
    }
    public function entries(){
        return $this->hasMany(Entry::class);
    }
   
    public function notification_user(){
		  return $this->hasMany(NotificationUser::class);
    }

	public function notifications()
	{
		return $this->belongsToMany('App\Notification');
	}
}
