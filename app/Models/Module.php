<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Module
 * 
 * @property int $id
 * @property string $libelle
 * @property int $is_externe
 * @property string $lien
 * @property string $icone
 * @property string $bg_color
 * @property string $text_color
 * @property int $sys_groupes_traitement_id
 *
 * @package App\Models
 */
class Module extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'is_externe' => 'int',
		'sys_groupes_traitement_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'is_externe',
		'lien',
		'icone',
		'bg_color',
		'text_color',
		'sys_groupes_traitement_id'
	];
}
