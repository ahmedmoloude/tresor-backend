<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Programmejour extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [



	];



	protected $fillable = [

		'libelle',
		'date',
		'etat'
	];
}
