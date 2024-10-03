<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class NomenclatureElement
 * 
 * @property int $id
 * @property int $ref_categorie_nomenclature_id
 * @property int $ref_type_nomenclature_id
 * @property string $code
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $niveau
 * @property int $parent
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\RefCategorieNomenclature $ref_categorie_nomenclature
 * @property \App\Models\RefTypeNomenclature $ref_type_nomenclature
 * @property \Illuminate\Database\Eloquent\Collection $budget_details
 * @property \Illuminate\Database\Eloquent\Collection $depenses
 * @property \App\Models\Recette $recette
 * @property \Illuminate\Database\Eloquent\Collection $rel_nomenclature_elements
 *
 * @package App\Models
 */
class NomenclatureElement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_categorie_nomenclature_id' => 'int',
		'ref_type_nomenclature_id' => 'int',
		'niveau' => 'int',
		'parent' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'ref_categorie_nomenclature_id',
		'ref_type_nomenclature_id',
		'code',
		'libelle',
		'libelle_ar',
		'niveau',
		'parent',
		'ordre'
	];

	public function ref_categorie_nomenclature()
	{
		return $this->belongsTo(\App\Models\RefCategorieNomenclature::class);
	}

	public function ref_type_nomenclature()
	{
		return $this->belongsTo(\App\Models\RefTypeNomenclature::class);
	}

	public function budget_details()
	{
		return $this->hasMany(\App\Models\BudgetDetail::class);
	}

	public function depenses()
	{
		return $this->hasMany(\App\Models\Depense::class);
	}

	public function recette()
	{
		return $this->hasOne(\App\Models\Recette::class);
	}

	public function rel_nomenclature_elements()
	{
		return $this->hasMany(\App\Models\RelNomenclatureElement::class);
	}
}
