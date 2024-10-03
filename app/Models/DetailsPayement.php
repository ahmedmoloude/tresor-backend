<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class DetailsPayement
 *
 * @property int $id
 * @property int $payement_id
 * @property float $montant
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Payement $payement
 *
 * @package App\Models
 */
class DetailsPayement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'payement_id' => 'int',
		'montant' => 'float'
	];

	protected $fillable = [
		'payement_id',
		'montant',
		'description'
	];

	public function payement()
	{
		return $this->belongsTo(\App\Models\Payement::class);
	}
}
