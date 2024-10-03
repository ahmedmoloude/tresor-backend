<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class PlansMaintenance
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $equipement_id
 * @property \Carbon\Carbon $date_plan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Equipement $equipement
 * @property \Illuminate\Database\Eloquent\Collection $items_plans_maintenances
 *
 * @package App\Models
 */
class PlansMaintenance extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'equipement_id' => 'int'
	];

	protected $dates = [
		'date_plan'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'equipement_id',
		'date_plan'
	];

	public function equipement()
	{
		return $this->belongsTo(\App\Models\Equipement::class);
	}

	public function items_plans_maintenances()
	{
		return $this->hasMany(\App\Models\ItemsPlansMaintenance::class);
	}
}
