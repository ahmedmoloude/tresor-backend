<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Employe
 * 
 * @property int $id
 * @property string $code
 * @property string $prenom
 * @property string $nom
 * @property string $nom_famille
 * @property string $nom_ar
 * @property string $prenom_ar
 * @property string $nom_famille_ar
 * @property string $nni
 * @property int $ref_genre_id
 * @property \Carbon\Carbon $date_naissance
 * @property int $lieu_naissance
 * @property int $ref_situation_familliale_id
 * @property string $photo
 * @property int $ref_niveau_etude_id
 * @property int $specialite_id
 * @property \Carbon\Carbon $date_embauche
 * @property int $service_id
 * @property int $ref_fonction_id
 * @property string $taches
 * @property int $ref_types_contrat_id
 * @property string $titre
 * @property string $salaire_mensuel
 * @property string $tel
 * @property string $email
 * @property string $adress
 * @property string $whatsapp
 * @property string $commentaires
 * @property int $ref_appreciations_hierarchie_id
 * @property string $prenom_personne
 * @property string $nom_personne
 * @property string $tel_personne
 * @property string $email_personne
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\RefAppreciationsHierarchy $ref_appreciations_hierarchy
 * @property \App\Models\Commune $commune
 * @property \App\Models\RefFonction $ref_fonction
 * @property \App\Models\RefGenre $ref_genre
 * @property \App\Models\RefNiveauEtude $ref_niveau_etude
 * @property \App\Models\RefSituationFamilliale $ref_situation_familliale
 * @property \App\Models\RefTypesContrat $ref_types_contrat
 * @property \App\Models\Specialite $specialite
 * @property \App\Models\Service $service
 *
 * @package App\Models
 */
class Employe extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_genre_id' => 'int',
		'lieu_naissance' => 'int',
		'ref_situation_familliale_id' => 'int',
		'ref_niveau_etude_id' => 'int',
		'specialite_id' => 'int',
		'service_id' => 'int',
		'ref_fonction_id' => 'int',
		'ref_types_contrat_id' => 'int',
		'ref_appreciations_hierarchie_id' => 'int'
	];

	protected $dates = [
		'date_naissance',
		'date_embauche'
	];

	protected $fillable = [
		'code',
		'prenom',
		'nom',
		'nom_famille',
		'nom_ar',
		'prenom_ar',
		'nom_famille_ar',
		'nni',
		'ref_genre_id',
		'date_naissance',
		'lieu_naissance',
		'ref_situation_familliale_id',
		'photo',
		'ref_niveau_etude_id',
		'specialite_id',
		'date_embauche',
		'service_id',
		'ref_fonction_id',
		'taches',
		'ref_types_contrat_id',
		'titre',
		'salaire_mensuel',
		'tel',
		'email',
		'adress',
		'whatsapp',
		'commentaires',
		'ref_appreciations_hierarchie_id',
		'prenom_personne',
		'nom_personne',
		'tel_personne',
		'email_personne'
	];

	public function ref_appreciations_hierarchy()
	{
		return $this->belongsTo(\App\Models\RefAppreciationsHierarchy::class, 'ref_appreciations_hierarchie_id');
	}

	public function commune()
	{
		return $this->belongsTo(\App\Models\Commune::class, 'lieu_naissance');
	}

	public function ref_fonction()
	{
		return $this->belongsTo(\App\Models\RefFonction::class);
	}

	public function ref_genre()
	{
		return $this->belongsTo(\App\Models\RefGenre::class);
	}

	public function ref_niveau_etude()
	{
		return $this->belongsTo(\App\Models\RefNiveauEtude::class);
	}

	public function ref_situation_familliale()
	{
		return $this->belongsTo(\App\Models\RefSituationFamilliale::class);
	}

	public function ref_types_contrat()
	{
		return $this->belongsTo(\App\Models\RefTypesContrat::class);
	}

	public function specialite()
	{
		return $this->belongsTo(\App\Models\Specialite::class);
	}

	public function service()
	{
		return $this->belongsTo(\App\Models\Service::class);
	}
}
