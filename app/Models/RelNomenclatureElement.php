<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RelNomenclatureElement
 * 
 * @property int $id
 * @property int $nomenclature_id
 * @property int $nomenclature_element_id
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\NomenclatureElement $nomenclature_element
 * @property \App\Models\Nomenclature $nomenclature
 *
 * @package App\Models
 */
class RelNomenclatureElement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'nomenclature_id' => 'int',
		'nomenclature_element_id' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'nomenclature_id',
		'nomenclature_element_id',
		'ordre'
	];

	public function nomenclature_element()
	{
		return $this->belongsTo(\App\Models\NomenclatureElement::class);
	}

	public function nomenclature()
	{
		return $this->belongsTo(\App\Models\Nomenclature::class);
	}
}
