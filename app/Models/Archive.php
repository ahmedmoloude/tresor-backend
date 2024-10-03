<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Archive extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	public function service()
	{
		return $this->belongsTo(\App\Models\Service::class, 'service_id');
	}

	public function type_archive()
	{
		return $this->belongsTo(\App\Models\RefTypeArchive::class, 'ref_type_archive_id');
	}

	public function emplacement()
	{
		return $this->belongsTo(\App\Models\ArEmplacement::class, 'ar_emplacement_id');
	}
}
