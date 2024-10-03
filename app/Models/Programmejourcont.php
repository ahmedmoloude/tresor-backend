<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Programmejourcont extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [



	];



	protected $fillable = [
		'programmejour_id',
		'contribuable_id',
		'etat'
	];
    public function contribuable()
    {
        return $this->belongsTo(\App\Models\Contribuable::class);
    }
    public function programmejour()
    {
        return $this->belongsTo(\App\Models\Programmejour::class);
    }
}
