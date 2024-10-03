<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 17 Jul 2020 14:38:34 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTailleActivite
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $contribuables
 * @property \Illuminate\Database\Eloquent\Collection $forchette_taxes
 *
 * @package App\Models
 */
class RefTailleActivite extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];

	public function contribuables()
	{
		return $this->hasMany(\App\Models\Contribuable::class);
	}

	public function forchette_taxes()
	{
		return $this->hasMany(\App\Models\ForchetteTax::class);
	}
}
