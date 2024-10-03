<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SysDroit
 * 
 * @property int $id
 * @property string $libelle
 * @property int $type_acces
 * @property int $sys_groupes_traitement_id
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $supprimer
 * 
 * @property \App\Models\SysGroupesTraitement $sys_groupes_traitement
 * @property \Illuminate\Database\Eloquent\Collection $sys_profiles
 *
 * @package App\Models
 */
class SysDroit extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'type_acces' => 'int',
		'sys_groupes_traitement_id' => 'int',
		'ordre' => 'int',
		'supprimer' => 'int'
	];

	protected $fillable = [
		'libelle',
		'type_acces',
		'sys_groupes_traitement_id',
		'ordre',
		'supprimer'
	];

	public function sys_groupes_traitement()
	{
		return $this->belongsTo(\App\Models\SysGroupesTraitement::class);
	}

	public function sys_profiles()
	{
		return $this->belongsToMany(\App\Models\SysProfile::class, 'sys_profiles_sys_droits')
					->withPivot('id', 'ordre', 'deleted_at')
					->withTimestamps();
	}
}
