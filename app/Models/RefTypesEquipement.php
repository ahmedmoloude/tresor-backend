<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTypesEquipement
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $type_affichage
 * @property string $image
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $equipements
 * @property \Illuminate\Database\Eloquent\Collection $ref_elements
 *
 * @package App\Models
 */
class RefTypesEquipement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'type_affichage' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'type_affichage',
		'image',
		'ordre'
	];

	public function equipements()
	{
		return $this->hasMany(\App\Models\Equipement::class);
	}

	public function ref_elements()
	{
		return $this->hasMany(\App\Models\RefElement::class);
	}
}
