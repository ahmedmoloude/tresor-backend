<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Commune
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $adresse_GPS
 * @property string $contour_gps
 * @property int $nbr_habitans
 * @property int $classe_population
 * @property int $moughataa_id
 * @property string $code
 * @property string $nom_Maire
 * @property string $nom_SG
 * @property int $surface
 * @property int $nbr_villages_localites
 * @property string $decret_de_creation
 * @property int $nbr_conseillers_municipaux
 * @property int $nbr_employes_municipaux_permanents
 * @property int $nbr_employes_municipaux_temporaires
 * @property int $secretaire_generale
 * @property bool $pnidelle
 * @property bool $organisations_internationale
 * @property bool $recettes_impots
 * @property bool $eclairage_public
 * @property string $path_carte
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Moughataa $moughataa
 * @property \Illuminate\Database\Eloquent\Collection $budgets
 * @property \Illuminate\Database\Eloquent\Collection $employes
 * @property \Illuminate\Database\Eloquent\Collection $localites
 * @property \Illuminate\Database\Eloquent\Collection $services
 *
 * @package App\Models
 */
class Commune extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'nbr_habitans' => 'int',
		'classe_population' => 'int',
		'moughataa_id' => 'int',
		'surface' => 'int',
		'nbr_villages_localites' => 'int',
		'nbr_conseillers_municipaux' => 'int',
		'nbr_employes_municipaux_permanents' => 'int',
		'nbr_employes_municipaux_temporaires' => 'int',
		'secretaire_generale' => 'int',
		'pnidelle' => 'bool',
		'organisations_internationale' => 'bool',
		'recettes_impots' => 'bool',
		'eclairage_public' => 'bool'
	];

	protected $hidden = [
		'secretaire_generale'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'adresse_GPS',
		'contour_gps',
		'nbr_habitans',
		'classe_population',
		'moughataa_id',
		'code',
		'nom_Maire',
		'nom_SG',
		'surface',
		'nbr_villages_localites',
		'decret_de_creation',
		'nbr_conseillers_municipaux',
		'nbr_employes_municipaux_permanents',
		'nbr_employes_municipaux_temporaires',
		'secretaire_generale',
		'pnidelle',
		'organisations_internationale',
		'recettes_impots',
		'eclairage_public',
		'path_carte'
	];

	public function moughataa()
	{
		return $this->belongsTo(\App\Models\Moughataa::class);
	}

	public function budgets()
	{
		return $this->hasMany(\App\Models\Budget::class);
	}

	public function employes()
	{
		return $this->hasMany(\App\Models\Employe::class, 'lieu_naissance');
	}

	public function localites()
	{
		return $this->hasMany(\App\Models\Localite::class);
	}

	public function services()
	{
		return $this->hasMany(\App\Models\Service::class);
	}
}
