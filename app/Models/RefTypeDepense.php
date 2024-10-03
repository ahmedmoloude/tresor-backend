<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefTypeDepense
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $depenses
 *
 * @package App\Models
 */
class RefTypeDepense extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];

	public function depenses()
	{
		return $this->hasMany(\App\Models\Depense::class, 'ref_type_depenses');
	}
}
