<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Secteur
 * 
 * @property int $id
 * @property int $parent
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $equipements
 *
 * @package App\Models
 */
class Secteur extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'parent' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'parent',
		'libelle',
		'libelle_ar',
		'ordre'
	];

	public function equipements()
	{
		return $this->hasMany(\App\Models\Equipement::class);
	}
}
