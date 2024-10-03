<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Mois
 *
 * @property int $id
 * @property int $libelle
 * @property int $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $payements
 *
 * @package App\Models
 */
class Mois extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [

	];

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];

	public function payements()
	{
		return $this->hasMany(\App\Models\Payement::class);
	}
}
