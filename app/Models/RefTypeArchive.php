<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTypeBudget
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $budgets
 *
 * @package App\Models
 */
class RefTypeArchive extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	public function archives()
	{
		return $this->hasMany(\App\Models\Archive::class);
	}
}