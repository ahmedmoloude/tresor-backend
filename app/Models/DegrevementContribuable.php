<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class DegrevementContribuable extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [

		'contribuable_id' => 'int',
        'article_id' => 'int',
        'protocol_id' => 'int',

	];
	protected $fillable = [
		'annee',
        'article_id',
		 'protocol_id',
		 'contribuable_id'
	];

	public function contribuable()
	{
		return $this->belongsTo(\App\Models\Contribuable::class);
	}
    public function article()
    {
        return $this->belongsTo(\App\Models\RolesContribuable::class);
    }
    public function protocol()
    {
        return $this->belongsTo(\App\Models\Protocole::class);
    }

}
