<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Wilaya
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $adresse_gps
 * @property string $contour_gps
 * @property float $nbr_habitants
 * @property string $code
 * @property string $path_carte
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $moughataas
 *
 * @package App\Models
 */
class Wilaya extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'nbr_habitants' => 'float'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'adresse_gps',
		'contour_gps',
		'nbr_habitants',
		'code',
		'path_carte'
	];

	public function moughataas()
	{
		return $this->hasMany(\App\Models\Moughataa::class);
	}
}
