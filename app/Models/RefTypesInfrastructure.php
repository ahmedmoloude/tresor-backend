<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTypesInfrastructure
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $infrastructures
 *
 * @package App\Models
 */
class RefTypesInfrastructure extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre'
	];

	public function infrastructures()
	{
		return $this->hasMany(\App\Models\Infrastructure::class);
	}
}
