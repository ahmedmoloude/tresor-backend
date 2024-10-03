<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArOrigine extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	public function archives()
	{
		return $this->hasMany(\App\Models\Courrier::class);
	}
}
