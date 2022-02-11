<?php

namespace App;

use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'user_id','store_id'
    ];
    public function users(){
        return $this->belongsTo(User::class);
    }
}
