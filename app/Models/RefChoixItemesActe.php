<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefChoixItemesActe
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $ordre
 * @property int $modeles_actes_item_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\ModelesActesItem $modeles_actes_item
 *
 * @package App\Models
 */
class RefChoixItemesActe extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ordre' => 'int',
		'modeles_actes_item_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'ordre',
		'modeles_actes_item_id'
	];

	public function modeles_actes_item()
	{
		return $this->belongsTo(\App\Models\ModelesActesItem::class);
	}
}
