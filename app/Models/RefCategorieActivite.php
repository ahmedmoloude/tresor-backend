<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 17 Jul 2020 14:38:34 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefCategorieActivite
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property float $montant
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $activites
 * @property \Illuminate\Database\Eloquent\Collection $forchette_taxes
 *
 * @package App\Models
 */
class RefCategorieActivite extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'montant' => 'float'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'montant'
	];

	public function activites()
	{
		return $this->hasMany(\App\Models\Activite::class);
	}

	public function forchette_taxes()
	{
		return $this->hasMany(\App\Models\ForchetteTax::class);
	}
}
