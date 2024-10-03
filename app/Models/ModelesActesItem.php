<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ModelesActesItem
 *
 * @property int $id
 * @property int $modeles_acte_id
 * @property int $nature_content
 * @property string $content_value
 * @property string $content_value_ar
 * @property int $type_content
 * @property int $ordre
 * @property string $postion
 * @property string $alignement
 * @property string $nom_item
 * @property int $parent
 * @property int $ligne
 * @property string $texte_secondaire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\ModelesActe $modeles_acte
 * @property \Illuminate\Database\Eloquent\Collection $actes_values
 * @property \Illuminate\Database\Eloquent\Collection $ref_choix_itemes_actes
 *
 * @package App\Models
 */
class ModelesActesItem extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'modeles_acte_id' => 'int',
		'nature_content' => 'int',
		'type_content' => 'int',
		'ordre' => 'int',
		'parent' => 'int',
		'ligne' => 'int'
	];

	protected $fillable = [
		'modeles_acte_id',
		'nature_content',
		'content_value',
		'content_value_ar',
		'type_content',
		'ordre',
		'postion',
		'alignement',
		'nom_item',
		'parent',
		'ligne',
		'texte_secondaire'
	];

	public function modeles_acte()
	{
		return $this->belongsTo(\App\Models\ModelesActe::class);
	}

	public function actes_values()
	{
		return $this->hasMany(\App\Models\ActesValue::class);
	}

	public function ref_choix_itemes_actes()
	{
		return $this->hasMany(\App\Models\RefChoixItemesActe::class);
	}
    public function getTypeTextAttribute()
    {
        $text='';
        switch ($this->type_content) {
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
            case '':
                $text=trans('text_me.fixe');;
                break;
        }
        return ucfirst($text);
    }
}
