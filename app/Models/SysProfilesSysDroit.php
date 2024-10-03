<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SysProfilesSysDroit
 * 
 * @property int $id
 * @property int $sys_profile_id
 * @property int $sys_droit_id
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\SysDroit $sys_droit
 * @property \App\Models\SysProfile $sys_profile
 *
 * @package App\Models
 */
class SysProfilesSysDroit extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'sys_profile_id' => 'int',
		'sys_droit_id' => 'int',
		'ordre' => 'int'
	];

	protected $fillable = [
		'sys_profile_id',
		'sys_droit_id',
		'ordre'
	];

	public function sys_droit()
	{
		return $this->belongsTo(\App\Models\SysDroit::class);
	}

	public function sys_profile()
	{
		return $this->belongsTo(\App\Models\SysProfile::class);
	}
}
