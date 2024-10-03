<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefElement
 *
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property int $type
 * @property int $ref_types_equipement_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefTypesEquipement $ref_types_equipement
 * @property \Illuminate\Database\Eloquent\Collection $equipements
 * @property \Illuminate\Database\Eloquent\Collection $ref_choix_elements
 *
 * @package App\Models
 */
class RefElement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'type' => 'int',
		'ref_types_equipement_id' => 'int'
	];

	protected $fillable = [
		'libelle',
		'libelle_ar',
		'type',
		'ref_types_equipement_id'
	];

	public function ref_types_equipement()
	{
		return $this->belongsTo(\App\Models\RefTypesEquipement::class);
	}

	public function equipements()
	{
		return $this->belongsToMany(\App\Models\Equipement::class, 'equipements_ref_elements')
					->withPivot('id', 'valeur', 'deleted_at')
					->withTimestamps();
	}

	public function ref_choix_elements()
	{
		return $this->hasMany(\App\Models\RefChoixElement::class);
	}
    public function getTypeTextAttribute()
    {
        $text='';
        switch ($this->type) {
            case '1':
                $text=trans('text_me.texte');
                break;
            case '2':
                $text=trans('text_me.numerique');
                break;
            case '3':
                $text=trans('text_me.choix');
                break;
            case '4':
                $text=trans('text_me.date');;
                break;
        }
        return ucfirst($text);
    }
}
