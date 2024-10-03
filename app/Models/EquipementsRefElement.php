<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class EquipementsRefElement
 * 
 * @property int $id
 * @property int $equipement_id
 * @property int $ref_element_id
 * @property string $valeur
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Equipement $equipement
 * @property \App\Models\RefElement $ref_element
 *
 * @package App\Models
 */
class EquipementsRefElement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'equipement_id' => 'int',
		'ref_element_id' => 'int'
	];

	protected $fillable = [
		'equipement_id',
		'ref_element_id',
		'valeur'
	];

	public function equipement()
	{
		return $this->belongsTo(\App\Models\Equipement::class);
	}

	public function ref_element()
	{
		return $this->belongsTo(\App\Models\RefElement::class);
	}
}
