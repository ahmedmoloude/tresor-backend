<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Famille
 * 
 * @property int $id
 * @property string $libelle
 * @property int $has_articles
 * @property int $ref_types_famille_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Famille extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'has_articles' => 'int',
		'ref_types_famille_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'has_articles',
		'ref_types_famille_id'
	];
}
