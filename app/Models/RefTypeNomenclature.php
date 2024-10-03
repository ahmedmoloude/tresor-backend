<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTypeNomenclature
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_ad
 * 
 * @property \Illuminate\Database\Eloquent\Collection $nomenclature_elements
 *
 * @package App\Models
 */
class RefTypeNomenclature extends Eloquent
{
	protected $dates = [
		'deleted_ad'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'deleted_ad'
	];

	public function nomenclature_elements()
	{
		return $this->hasMany(\App\Models\NomenclatureElement::class);
	}
}
