<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Equipement
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $code
 * @property \Carbon\Carbon $date_acquisition
 * @property int $localite_id
 * @property string $deliberatin_patrimoine_communal
 * @property string $image
 * @property bool $Eau
 * @property bool $electricite
 * @property bool $service_hygiene_assainissement
 * @property bool $accessibilite
 * @property string $situation_environnementale
 * @property int $ref_types_equipement_id
 * @property bool $patrimoine_public
 * @property string $num_deliberation
 * @property \Carbon\Carbon $date_deliberation
 * @property int $user_id
 * @property int $ancien_eq
 * @property int $active
 * @property int $secteur_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefTypesEquipement $ref_types_equipement
 * @property \App\Models\Secteur $secteur
 * @property \App\Models\Localite $localite
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $coordonnees_equipements_geos
 * @property \Illuminate\Database\Eloquent\Collection $ref_elements
 * @property \Illuminate\Database\Eloquent\Collection $infrastructures
 * @property \Illuminate\Database\Eloquent\Collection $plans_maintenances
 *
 * @package App\Models
 */
class Equipement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'localite_id' => 'int',
		'Eau' => 'bool',
		'electricite' => 'bool',
		'service_hygiene_assainissement' => 'bool',
		'accessibilite' => 'bool',
		'ref_types_equipement_id' => 'int',
		'patrimoine_public' => 'bool',
		'user_id' => 'int',
		'ancien_eq' => 'int',
		'active' => 'int',
		'secteur_id' => 'int'
	];

	protected $dates = [
		'date_acquisition'=>'date::d-m-Y',
		'date_deliberation' =>'date::d-m-Y'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'code',
		'date_acquisition',
		'localite_id',
		'deliberatin_patrimoine_communal',
		'image',
		'Eau',
		'electricite',
		'service_hygiene_assainissement',
		'accessibilite',
		'situation_environnementale',
		'ref_types_equipement_id',
		'patrimoine_public',
		'num_deliberation',
		'date_deliberation',
		'user_id',
		'ancien_eq',
		'active',
		'secteur_id'
	];

	public function ref_types_equipement()
	{
		return $this->belongsTo(\App\Models\RefTypesEquipement::class);
	}

	public function secteur()
	{
		return $this->belongsTo(\App\Models\Secteur::class);
	}

	public function localite()
	{
		return $this->belongsTo(\App\Models\Localite::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function coordonnees_equipements_geos()
	{
		return $this->hasMany(\App\Models\CoordonneesEquipementsGeo::class);
	}

    public function get_coordonnees_equipements_geo()
    {
        return $this->hasOne(\App\Models\CoordonneesEquipementsGeo::class);
    }

	public function ref_elements()
	{
		return $this->belongsToMany(\App\Models\RefElement::class, 'equipements_ref_elements')
					->withPivot('id', 'valeur', 'deleted_at')
					->withTimestamps();
	}

	public function infrastructures()
	{
		return $this->hasMany(\App\Models\Infrastructure::class);
	}

	public function plans_maintenances()
	{
		return $this->hasMany(\App\Models\PlansMaintenance::class);
	}
}
