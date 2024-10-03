<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class RefNiveauImportance extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	
	public function archives()
	{
		return $this->hasMany(\App\Models\Courrier::class, 'ref_niveau_importances');
	}
}
