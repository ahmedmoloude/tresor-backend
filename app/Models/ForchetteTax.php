<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 17 Jul 2020 14:38:34 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ForchetteTax
 *
 * @property int $id
 * @property int $ref_emplacement_activite_id
 * @property int $ref_taille_activite_id
 * @property int $ref_categorie_activite_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefCategorieActivite $ref_categorie_activite
 * @property \App\Models\RefEmplacementActivite $ref_emplacement_activite
 * @property \App\Models\RefTailleActivite $ref_taille_activite
 *
 * @package App\Models
 */
class ForchetteTax extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_emplacement_activite_id' => 'int',
		'ref_taille_activite_id' => 'int',
		'ref_categorie_activite_id' => 'int'
	];

	protected $fillable = [
		'ref_emplacement_activite_id',
		'ref_taille_activite_id',
		'ref_categorie_activite_id'
	];

	public function ref_categorie_activite()
	{
		return $this->belongsTo(\App\Models\RefCategorieActivite::class);
	}

	public function ref_emplacement_activite()
	{
		return $this->belongsTo(\App\Models\RefEmplacementActivite::class);
	}

	public function ref_taille_activite()
	{
		return $this->belongsTo(\App\Models\RefTailleActivite::class);
	}
}
