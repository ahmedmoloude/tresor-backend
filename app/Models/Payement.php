<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 Jul 2020 11:48:03 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Payement
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property string $annee
 * @property int $protocol_id
 * @property int $contribuable_id
 * @property int $etat
 * @property float $montant
 * @property float $montant_arriere
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Contribuable $contribuable
 * @property \App\Models\Mois $mois
 * @property \Illuminate\Database\Eloquent\Collection $details_payements
 *
 * @package App\Models
 */
class Payement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'protocol_id' => 'int',
		'contribuable_id' => 'int',
		'etat' => 'int',
		'montant' => 'float',
		'montant_arriere' => 'float'
	];

    protected $dates = [
        'date'=>'date::d-m-Y'
    ];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'annee',
		'protocol_id',
		'contribuable_id',
		'etat',
		'montant',
		'montant_arriere',
        'date'
	];

	public function contribuable()
	{
		return $this->belongsTo(\App\Models\Contribuable::class);
	}

	public function protocol()
	{
		return $this->belongsTo(\App\Models\Protocole::class);
	}

	public function details_payements()
	{
		return $this->hasMany(\App\Models\DetailsPayement::class);
	}
}
