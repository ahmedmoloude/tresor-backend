<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Experience
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $service_id
 * @property int $annee_deb
 * @property int $mois_deb
 * @property int $jour_deb
 * @property int $annee_fin
 * @property int $mois_fin
 * @property int $jour_fin

 * @property string $mission_principal
 * @property int $employe_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\DemendeurEmplois $demendeur_emplois
 * @property \App\Models\Secteur $secteur
 * @property \App\Models\RefTypesExperience $ref_types_experience
 * @property \App\Models\Pay $pay
 *
 * @package App\Models
 */
class Experience extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'service_id' => 'int',
		'annee_deb' => 'int',
		'mois_deb' => 'int',
		'jour_deb' => 'int',
		'annee_fin' => 'int',
		'mois_fin' => 'int',
		'jour_fin' => 'int',
		'employe_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'service_id',
		'annee_deb',
		'mois_deb',
		'jour_deb',
		'annee_fin',
		'mois_fin',
		'jour_fin',
		'mission_principal',
		'employe_id'
	];

	public function employes()
	{
		return $this->belongsTo(\App\Models\Employe::class, 'employe_id');
	}

	public function service()
	{
		return $this->belongsTo(\App\Models\Service::class);
	}


}
