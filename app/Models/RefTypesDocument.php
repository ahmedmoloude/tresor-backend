<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTypesDocument
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $geds
 *
 * @package App\Models
 */
class RefTypesDocument extends Eloquent
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

	public function geds()
	{
		return $this->hasMany(\App\Models\Ged::class);
	}
}
