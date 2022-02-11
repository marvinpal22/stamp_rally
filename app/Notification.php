<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
	use SoftDeletes;

	protected $guarded = [];

	public function users()
	{
		return $this->belongsToMany('App\User')->withTimestamps();
	}

	//if a notification is created.
    protected static function boot()
    {
		parent::boot();

		$users = User::select('id')->where('role',1)->get();
		$ids = collect($users)->pluck('id');

        static::created(function ($notification) use($ids){
            $notification->users()->attach($ids);

		});
	}
	
	public function NotificationUser()
	{
		return $this->hasMany(NotificationUser::class);
	}
	
}
