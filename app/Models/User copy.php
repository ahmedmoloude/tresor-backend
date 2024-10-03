<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property int $sys_types_user_id
 * @property int $etat
 * @property string $phone
 * @property string $code
 * @property int $confirm
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\SysTypesUser $sys_types_user
 * @property \Illuminate\Database\Eloquent\Collection $depenses
 * @property \Illuminate\Database\Eloquent\Collection $equipements
 * @property \Illuminate\Database\Eloquent\Collection $recettes
 * @property \Illuminate\Database\Eloquent\Collection $sys_profiles
 *
 * @package App\Models
 */
class User extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'sys_types_user_id' => 'int',
		'etat' => 'int',
		'confirm' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'username',
		'email',
		'password',
		'remember_token',
		'sys_types_user_id',
		'etat',
		'phone',
		'code',
		'confirm'
	];

	public function sys_types_user()
	{
		return $this->belongsTo(\App\Models\SysTypesUser::class);
	}

	public function depenses()
	{
		return $this->hasMany(\App\Models\Depense::class);
	}

	public function equipements()
	{
		return $this->hasMany(\App\Models\Equipement::class);
	}

	public function recettes()
	{
		return $this->hasMany(\App\Models\Recette::class);
	}

	public function sys_profiles()
	{
		return $this->belongsToMany(\App\Models\SysProfile::class, 'sys_profiles_users')
					->withPivot('id', 'commune_id', 'ordre', 'deleted_at')
					->withTimestamps();
	}
}
