<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Sep 2020 19:01:26 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SigLayout
 *
 * @property int $id
 * @property string $libelle
 * @property string $crs_name
 * @property int $niveau
 * @property int $ref_types_objets_geo_id
 * @property int $ref_sig_type_geometry_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefSigTypeGeometry $ref_sig_type_geometry
 * @property \Illuminate\Database\Eloquent\Collection $sig_objects_layouts
 *
 * @package App\Models
 */
class SigLayout extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'niveau' => 'int',
		'ref_types_objets_geo_id' => 'int',
		'ref_sig_type_geometry_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'crs_name',
		'niveau',
		'ref_types_objets_geo_id',
		'ref_sig_type_geometry_id'
	];

	public function ref_sig_type_geometry()
	{
		return $this->belongsTo(\App\Models\RefSigTypeGeometry::class);
	}

	public function sig_objects_layouts()
	{
		return $this->hasMany(\App\Models\SigObjectsLayout::class);
	}
}
