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
class Echeance extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'protocol_id' => 'int',

	];

    protected $dates = [
        'dateEch'=>'date::d-m-Y'
    ];

	protected $fillable = [

		'protocol_id',
		'montant',
        'etat',
        'dateEch'
	];



	public function protocol()
	{
		return $this->belongsTo(\App\Models\Protocole::class);
	}


}
