<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefCategorieNomenclature
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $activites
 * @property \Illuminate\Database\Eloquent\Collection $nomenclature_elements
 *
 * @package App\Models
 */
class RefCategorieNomenclature extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];


	public function nomenclature_elements()
	{
		return $this->hasMany(\App\Models\NomenclatureElement::class);
	}
}
