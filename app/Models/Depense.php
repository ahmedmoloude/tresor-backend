<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Depense
 *
 * @property int $id
 * @property string $annee
 * @property int $ref_type_depenses
 * @property int $nomenclature_element_id
 * @property \Carbon\Carbon $date
 * @property float $montant
 * @property int $user_id
 * @property string $ged
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\NomenclatureElement $nomenclature_element
 * @property \App\Models\RefTypeDepense $ref_type_depense
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Depense extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_type_depenses' => 'int',
		'nomenclature_element_id' => 'int',
		'montant' => 'float',
		'user_id' => 'int'
	];

	protected $dates = [
		'date'=>'date::d-m-Y'
	];

	protected $fillable = [
		'annee',
		'ref_type_depenses',
		'nomenclature_element_id',
		'date',
		'montant',
		'user_id',
		'ged'
	];

	public function nomenclature_element()
	{
		return $this->belongsTo(\App\Models\NomenclatureElement::class);
	}

	public function ref_type_depense()
	{
		return $this->belongsTo(\App\Models\RefTypeDepense::class, 'ref_type_depenses');
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
