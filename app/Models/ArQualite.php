<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArQualite extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	public function archives()
	{
		return $this->hasMany(\App\Models\Archive::class);
	}
}
