<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class ContribuablesAnnee extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [

		'contribuable_id' => 'int',

	];



	protected $fillable = [

		'annee',
		'contribuable_id',
        'spontane'
	];

	public function contribuable()
	{
		return $this->belongsTo(\App\Models\Contribuable::class);
	}


}
