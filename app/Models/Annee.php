<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Nomenclature
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $budgets
 * @property \Illuminate\Database\Eloquent\Collection $rel_nomenclature_elements
 *
 * @package App\Models
 */
class Annee extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $casts = [
        'etat' => 'int'
    ];

	protected $fillable = [
		'annee',
        'etat'
	];
}
