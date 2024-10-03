<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Infrastructure
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $code
 * @property string $description
 * @property \Carbon\Carbon $date_construction
 * @property int $ref_types_infrastructure_id
 * @property int $equipement_id
 * @property int $ref_etats_infrastructure_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Equipement $equipement
 * @property \App\Models\RefTypesInfrastructure $ref_types_infrastructure
 * @property \App\Models\RefEtatsInfrastructure $ref_etats_infrastructure
 * @property \Illuminate\Database\Eloquent\Collection $items_plans_maintenances
 *
 * @package App\Models
 */
class Infrastructure extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_types_infrastructure_id' => 'int',
		'equipement_id' => 'int',
		'ref_etats_infrastructure_id' => 'int'
	];

	protected $dates = [
		'date_construction'=>'date::d-m-Y'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'code',
		'description',
		'date_construction',
		'ref_types_infrastructure_id',
		'equipement_id',
		'ref_etats_infrastructure_id'
	];

	public function equipement()
	{
		return $this->belongsTo(\App\Models\Equipement::class);
	}

	public function ref_types_infrastructure()
	{
		return $this->belongsTo(\App\Models\RefTypesInfrastructure::class);
	}

	public function ref_etats_infrastructure()
	{
		return $this->belongsTo(\App\Models\RefEtatsInfrastructure::class);
	}

	public function items_plans_maintenances()
	{
		return $this->hasMany(\App\Models\ItemsPlansMaintenance::class);
	}
}
