<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Service
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property int $commune_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Commune $commune
 * @property \Illuminate\Database\Eloquent\Collection $employes
 *
 * @package App\Models
 */
class Service extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ordre' => 'int',
		'commune_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre',
		'commune_id'
	];

	public function commune()
	{
		return $this->belongsTo(\App\Models\Commune::class);
	}

	public function employes()
	{
		return $this->hasMany(\App\Models\Employe::class);
	}
}
