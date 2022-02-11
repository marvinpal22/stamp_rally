<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name', 'address', 'industry','image', 'store_qr_code', 'fax', 'tel', 'hours',
		'tel','fax', 'hours', 'regular_holiday', 'stamping_conditions', 'service',
	];
	//

    // public function user(){
    //     return $this->belo(User::class);
	// }
	public function user()
	{
		return $this->belongsToMany('App\User','entries', 'user_id', 'store_id');
	}
}


