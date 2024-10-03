<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SysProfilesUser
 * 
 * @property int $id
 * @property int $sys_profile_id
 * @property int $user_id
 * @property int $commune_id
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\SysProfile $sys_profile
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class SysProfilesUser extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'sys_profile_id' => 'int',
		'user_id' => 'int',
		'commune_id' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'sys_profile_id',
		'user_id',
		'commune_id',
		'ordre'
	];

	public function sys_profile()
	{
		return $this->belongsTo(\App\Models\SysProfile::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
