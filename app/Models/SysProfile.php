<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SysProfile
 * 
 * @property int $id
 * @property string $libelle
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $sys_droits
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class SysProfile extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ordre' => 'int'
	];

	protected $fillable = [
		'libelle',
		'ordre'
	];

	public function sys_droits()
	{
		return $this->belongsToMany(\App\Models\SysDroit::class, 'sys_profiles_sys_droits')
					->withPivot('id', 'ordre', 'deleted_at')
					->withTimestamps();
	}

	public function users()
	{
		return $this->belongsToMany(\App\Models\User::class, 'sys_profiles_users')
					->withPivot('id', 'commune_id', 'ordre', 'deleted_at')
					->withTimestamps();
	}
}
