<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class backupstore extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name', 'address', 'industry','image', 'store_qr_code', 'fax', 'tel', 'hours',
		'tel','fax', 'hours', 'regular_holiday', 'stamping_conditions', 'service',
	];
}
