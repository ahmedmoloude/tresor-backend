<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class RolesContribuable extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [

		'contribuable_id' => 'int',
        'role_id' => 'int',

	];
	protected $fillable = [
		'annee',
        'role_id',
		'contribuable_id',
		'montant',
		'article',
	];

	public function contribuable()
	{
		return $this->belongsTo(\App\Models\Contribuable::class);
	}
    public function role()
    {
        return $this->belongsTo(\App\Models\RolesAnnee::class);
    }

}
