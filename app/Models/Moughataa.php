<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Moughataa
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $adresse_gps
 * @property string $Contour_pgs
 * @property int $nbr_habitants
 * @property int $wilaya_id
 * @property string $code
 * @property string $path_carte
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Wilaya $wilaya
 * @property \Illuminate\Database\Eloquent\Collection $communes
 *
 * @package App\Models
 */
class Moughataa extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'nbr_habitants' => 'int',
		'wilaya_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'adresse_gps',
		'Contour_pgs',
		'nbr_habitants',
		'wilaya_id',
		'code',
		'path_carte'
	];

	public function wilaya()
	{
		return $this->belongsTo(\App\Models\Wilaya::class);
	}

	public function communes()
	{
		return $this->hasMany(\App\Models\Commune::class);
	}
}
