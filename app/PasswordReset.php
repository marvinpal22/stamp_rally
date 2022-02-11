<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
	use SoftDeltes;
    //
    protected $fillable = [
        'email', 'token'
    ];
}
