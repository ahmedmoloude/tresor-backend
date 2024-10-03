<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemsPlansMaintenance
 * 
 * @property int $id
 * @property int $plans_maintenance_id
 * @property string $commentaires
 * @property int $infrastructure_id
 * @property int $ref_types_maintenance_id
 * @property \Carbon\Carbon $date_de
 * @property \Carbon\Carbon $date_fin
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Infrastructure $infrastructure
 * @property \App\Models\PlansMaintenance $plans_maintenance
 * @property \App\Models\RefTypesMaintenance $ref_types_maintenance
 * @property \Illuminate\Database\Eloquent\Collection $suivis_items_plans_maintenances
 *
 * @package App\Models
 */
class ItemsPlansMaintenance extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'plans_maintenance_id' => 'int',
		'infrastructure_id' => 'int',
		'ref_types_maintenance_id' => 'int'
	];

	protected $dates = [
		'date_de',
		'date_fin'
	];

	protected $fillable = [
		'plans_maintenance_id',
		'commentaires',
		'infrastructure_id',
		'ref_types_maintenance_id',
		'date_de',
		'date_fin'
	];

	public function infrastructure()
	{
		return $this->belongsTo(\App\Models\Infrastructure::class);
	}

	public function plans_maintenance()
	{
		return $this->belongsTo(\App\Models\PlansMaintenance::class);
	}

	public function ref_types_maintenance()
	{
		return $this->belongsTo(\App\Models\RefTypesMaintenance::class);
	}

	public function suivis_items_plans_maintenances()
	{
		return $this->hasMany(\App\Models\SuivisItemsPlansMaintenance::class);
	}
}
