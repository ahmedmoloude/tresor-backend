<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 17 Jul 2020 14:38:34 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Protocole
 *
 * @property int $id
 * @property int $annee_id
 * @property int $contribuable_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Contribuable $contribuable
 * @property \App\Models\Annee $annee
 *
 * @package App\Models
 */
class Protocole extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'annee_id' => 'int',
		'contribuable_id' => 'int'
	];

	protected $fillable = [
		'annee_id',
		'libelle',
		'montant',
		'montant_arriere',
		'remarque',
		'dateEch',
		'etat',
		'contribuable_id'
	];
    protected $dates = [
        'dateEch'=>'date::d-m-Y'
    ];
	public function contribuable()
	{
		return $this->belongsTo(\App\Models\Contribuable::class);
	}

	public function annee()
	{
		return $this->belongsTo(\App\Models\Annee::class);
	}
}
