<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class BudgetDetail
 *
 * @property int $id
 * @property int $budget_id
 * @property int $nomenclature_element_id
 * @property float $montant
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Budget $budget
 * @property \App\Models\NomenclatureElement $nomenclature_element
 *
 * @package App\Models
 */
class BudgetDetail extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'budget_id' => 'int',
		'nomenclature_element_id' => 'int',
		'montant' => 'float',
		'montant_realise' => 'int'
	];

	protected $fillable = [
		'budget_id',
		'nomenclature_element_id',
		'montant_realise',
		'montant',
	];

	public function budget()
	{
		return $this->belongsTo(\App\Models\Budget::class);
	}

	public function nomenclature_element()
	{
		return $this->belongsTo(\App\Models\NomenclatureElement::class);
	}
}
