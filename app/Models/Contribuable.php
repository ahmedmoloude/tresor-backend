<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Contribuable
 *
 * @property int $id
 * @property int $activite_id
 * @property int $ref_emplacement_activite_id
 * @property int $ref_taille_activite_id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $representant
 * @property string $adresse
 * @property int $telephone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefTailleActivite $ref_taille_activite
 * @property \App\Models\Activite $activite
 * @property \App\Models\RefEmplacementActivite $ref_emplacement_activite
 *
 * @package App\Models
 */
class Contribuable extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'contribuables';

	protected $casts = [
		'activite_id' => 'int',
		'ref_emplacement_activite_id' => 'int',
		'ref_taille_activite_id' => 'int',
		'telephone' => 'int'
	];
    protected $dates = [
        'date_mas'=>'date::d-m-Y'
    ];
	protected $fillable = [
		'activite_id',
		'ref_emplacement_activite_id',
		'ref_taille_activite_id',
		'libelle',
		'libelle_ar',
		'representant',
		'adresse',
		'telephone',
        'date_mas',
        'montant',
		'article'
	];

	public function ref_taille_activite()
	{
		return $this->belongsTo(\App\Models\RefTailleActivite::class);
	}

	public function activite()
	{
		return $this->belongsTo(\App\Models\Activite::class);
	}

	public function ref_emplacement_activite()
	{
		return $this->belongsTo(\App\Models\RefEmplacementActivite::class);
	}

    public function mois_services()
    {
        return $this->hasMany(\App\Models\MoisService::class);
    }

    public function payements()
    {
        return $this->hasMany(\App\Models\Payement::class);
    }

    public function contribuables_annees()
    {
        return $this->hasMany(\App\Models\ContribuablesAnnee::class);
    }

	public function rolesContribuables()
    {
        return $this->hasMany(RolesContribuable::class);
    }
}
