<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Sep 2020 19:01:26 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SigCoordinatesObject
 *
 * @property int $id
 * @property float $loguitude
 * @property float $latitude
 * @property int $sig_objects_layout_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\SigObjectsLayout $sig_objects_layout
 *
 * @package App\Models
 */
class SigCoordinatesObject extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'loguitude' => 'float',
		'latitude' => 'float',
		'sig_objects_layout_id' => 'int'
	];

	protected $fillable = [
		'loguitude',
		'latitude',
		'sig_objects_layout_id'
	];

	public function sig_objects_layout()
	{
		return $this->belongsTo(\App\Models\SigObjectsLayout::class);
	}
}
