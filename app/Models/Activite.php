<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Activite
 *
 * @property int $id
 * @property int $ref_categorie_activite_id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefCategorieNomenclature $ref_categorie_nomenclature
 * @property \Illuminate\Database\Eloquent\Collection $contribuables
 *
 * @package App\Models
 */
class Activite extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_categorie_activite_id' => 'int'
	];

	protected $fillable = [
		'ref_categorie_activite_id',
		'libelle',
		'libelle_ar'
	];

	public function ref_categorie_activite()
	{
		return $this->belongsTo(\App\Models\RefCategorieActivite::class, 'ref_categorie_activite_id');
	}

	public function contribuables()
	{
		return $this->hasMany(\App\Models\Contribuable::class);
	}
}
