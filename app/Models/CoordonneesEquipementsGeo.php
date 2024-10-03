<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class CoordonneesEquipementsGeo
 * 
 * @property int $id
 * @property int $equipement_id
 * @property float $lat
 * @property float $lng
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Equipement $equipement
 *
 * @package App\Models
 */
class CoordonneesEquipementsGeo extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'coordonnees_equipements_geo';

	protected $casts = [
		'equipement_id' => 'int',
		'lat' => 'float',
		'lng' => 'float',
		'ordre' => 'int'
	];

	protected $fillable = [
		'equipement_id',
		'lat',
		'lng',
		'ordre'
	];

	public function equipement()
	{
		return $this->belongsTo(\App\Models\Equipement::class);
	}
}
