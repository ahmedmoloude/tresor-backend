<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Acte
 *
 * @property int $id
 * @property int $modeles_acte_id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $date
 * @property int $num
 * @property int $etat
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\ModelesActe $modeles_acte
 * @property \Illuminate\Database\Eloquent\Collection $actes_values
 *
 * @package App\Models
 */
class Acte extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'modeles_acte_id' => 'int',
		'num' => 'int',
		'etat' => 'int'
	];

	protected $dates = [
		'date'=>'date::d-m-Y'
	];

	protected $fillable = [
		'modeles_acte_id',
		'libelle',
		'libelle_ar',
		'date',
		'num',
		'etat'
	];

	public function modeles_acte()
	{
		return $this->belongsTo(\App\Models\ModelesActe::class);
	}

	public function actes_values()
	{
		return $this->hasMany(\App\Models\ActesValue::class);
	}
}
