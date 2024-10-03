<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefChoixElement
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property int $ref_element_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\RefElement $ref_element
 *
 * @package App\Models
 */
class RefChoixElement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ordre' => 'int',
		'ref_element_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre',
		'ref_element_id'
	];

	public function ref_element()
	{
		return $this->belongsTo(\App\Models\RefElement::class);
	}
}
