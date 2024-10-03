<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Sep 2020 17:37:13 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefSigTypeGeometry
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class RefSigTypeGeometry extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'ref_sig_type_geometrys';

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre'
	];
}
