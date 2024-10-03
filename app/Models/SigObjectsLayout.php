<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Sep 2020 19:01:26 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SigObjectsLayout
 *
 * @property int $id
 * @property int $sig_layout_id
 * @property int $object_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\SigLayout $sig_layout
 * @property \Illuminate\Database\Eloquent\Collection $sig_coordinates_objects
 *
 * @package App\Models
 */
class SigObjectsLayout extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'sig_layout_id' => 'int',
		'object_id' => 'int'
	];

	protected $fillable = [
		'sig_layout_id',
		'object_id'
	];

	public function sig_layout()
	{
		return $this->belongsTo(\App\Models\SigLayout::class);
	}

	public function sig_coordinates_objects()
	{
		return $this->hasMany(\App\Models\SigCoordinatesObject::class);
	}
}
