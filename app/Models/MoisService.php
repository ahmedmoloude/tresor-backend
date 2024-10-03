<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 17 Jul 2020 14:38:34 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class MoisService
 *
 * @property int $id
 * @property int $mois_id
 * @property int $contribuable_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Contribuable $contribuable
 * @property \App\Models\Mois $mois
 *
 * @package App\Models
 */
class MoisService extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'mois_id' => 'int',
		'contribuable_id' => 'int'
	];

	protected $fillable = [
		'mois_id',
		'contribuable_id',
		'annee'
	];

	public function contribuable()
	{
		return $this->belongsTo(\App\Models\Contribuable::class);
	}

	public function mois()
	{
		return $this->belongsTo(\App\Models\Mois::class);
	}
}
