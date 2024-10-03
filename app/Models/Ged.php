<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Ged
 * 
 * @property int $id
 * @property string $libelle
 * @property string $emplacement
 * @property int $objet_id
 * @property int $type
 * @property string $extension
 * @property int $ref_types_document_id
 * @property string $commentaire
 * @property int $taille
 * @property int $type_ged
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\RefTypesDocument $ref_types_document
 *
 * @package App\Models
 */
class Ged extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'ged';

	protected $casts = [
		'objet_id' => 'int',
		'type' => 'int',
		'ref_types_document_id' => 'int',
		'taille' => 'int',
		'type_ged' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'emplacement',
		'objet_id',
		'type',
		'extension',
		'ref_types_document_id',
		'commentaire',
		'taille',
		'type_ged',
		'ordre'
	];

	public function ref_types_document()
	{
		return $this->belongsTo(\App\Models\RefTypesDocument::class);
	}
}
