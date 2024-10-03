<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Nomenclature
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $budgets
 * @property \Illuminate\Database\Eloquent\Collection $rel_nomenclature_elements
 *
 * @package App\Models
 */
class Nomenclature extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];

	public function budgets()
	{
		return $this->hasMany(\App\Models\Budget::class);
	}

	public function rel_nomenclature_elements()
	{
		return $this->hasMany(\App\Models\RelNomenclatureElement::class);
	}
}
