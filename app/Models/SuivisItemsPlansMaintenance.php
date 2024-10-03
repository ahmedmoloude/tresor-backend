<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SuivisItemsPlansMaintenance
 * 
 * @property int $id
 * @property int $items_plans_maintenance_id
 * @property \Carbon\Carbon $date_suivi
 * @property int $ref_etats_avancement_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\ItemsPlansMaintenance $items_plans_maintenance
 * @property \App\Models\RefEtatsAvancement $ref_etats_avancement
 *
 * @package App\Models
 */
class SuivisItemsPlansMaintenance extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'items_plans_maintenance_id' => 'int',
		'ref_etats_avancement_id' => 'int'
	];

	protected $dates = [
		'date_suivi'
	];

	protected $fillable = [
		'items_plans_maintenance_id',
		'date_suivi',
		'ref_etats_avancement_id'
	];

	public function items_plans_maintenance()
	{
		return $this->belongsTo(\App\Models\ItemsPlansMaintenance::class);
	}

	public function ref_etats_avancement()
	{
		return $this->belongsTo(\App\Models\RefEtatsAvancement::class);
	}
}
