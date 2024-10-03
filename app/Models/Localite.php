<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Localite
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $coordonnees_gps
 * @property int $commune_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Commune $commune
 * @property \Illuminate\Database\Eloquent\Collection $equipements
 *
 * @package App\Models
 */
class Localite extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'commune_id' => 'int',
        'population' => 'int',
        'surface' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'coordonnees_gps',
		'commune_id',
        'population' ,
        'surface'
	];

	public function commune()
	{
		return $this->belongsTo(\App\Models\Commune::class);
	}

	public function equipements()
	{
		return $this->hasMany(\App\Models\Equipement::class);
	}
    public function emplacements()
    {
        return $this->hasMany(Emplacement::class);
    }
}
