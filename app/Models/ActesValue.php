<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ActesValue
 * 
 * @property int $id
 * @property int $acte_id
 * @property int $modeles_actes_item_id
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Acte $acte
 * @property \App\Models\ModelesActesItem $modeles_actes_item
 *
 * @package App\Models
 */
class ActesValue extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'acte_id' => 'int',
		'modeles_actes_item_id' => 'int'
	];

	protected $fillable = [
		'acte_id',
		'modeles_actes_item_id',
		'value'
	];

	public function acte()
	{
		return $this->belongsTo(\App\Models\Acte::class);
	}

	public function modeles_actes_item()
	{
		return $this->belongsTo(\App\Models\ModelesActesItem::class);
	}
}
