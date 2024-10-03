<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ModelesActe
 * 
 * @property int $id
 * @property string $titre
 * @property string $titre_ar
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $actes
 * @property \Illuminate\Database\Eloquent\Collection $modeles_actes_items
 *
 * @package App\Models
 */
class ModelesActe extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'titre',
		'titre_ar',
		'libelle',
		'libelle_ar'
	];

	public function actes()
	{
		return $this->hasMany(\App\Models\Acte::class);
	}

	public function modeles_actes_items()
	{
		return $this->hasMany(\App\Models\ModelesActesItem::class);
	}
}
