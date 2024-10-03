<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefEtatsInfrastructure
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $odre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $infrastructures
 *
 * @package App\Models
 */
class RefEtatsInfrastructure extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'odre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'odre'
	];

	public function infrastructures()
	{
		return $this->hasMany(\App\Models\Infrastructure::class);
	}
}
