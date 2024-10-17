<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Budget
 * 
 * @property int $id
 * @property int $commune_id
 * @property int $nomenclature_id
 * @property string $annee
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ref_type_budget_id
 * @property int $ordre_complementaire
 * @property int $ref_etat_budget_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Nomenclature $nomenclature
 * @property \App\Models\RefTypeBudget $ref_type_budget
 * @property \App\Models\Commune $commune
 * @property \Illuminate\Database\Eloquent\Collection $budget_details
 *
 * @package App\Models
 */
class Budget extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'commune_id' => 'int',
		'nomenclature_id' => 'int',
		'ref_type_budget_id' => 'int',
		'ordre_complementaire' => 'int',
		'ref_etat_budget_id' => 'int'
	];

	protected $fillable = [
		'commune_id',
		'nomenclature_id',
		'annee',
		'libelle',
		'libelle_ar',
		'ref_type_budget_id',
		'ordre_complementaire',
		'ref_etat_budget_id',
		'income_document',
		'expense_document'
	];

	public function nomenclature()
	{
		return $this->belongsTo(\App\Models\Nomenclature::class);
	}

	public function ref_type_budget()
	{
		return $this->belongsTo(\App\Models\RefTypeBudget::class);
	}

	public function commune()
	{
		return $this->belongsTo(\App\Models\Commune::class);
	}

	public function budget_details()
	{
		return $this->hasMany(\App\Models\BudgetDetail::class);
	}
}
