<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Emplacement
 *
 * @property int $id
 * @property int $ref_type_emplacement_id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property string|null $description
 * @property string|null $description_ar
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property RefTypeEmplacement $ref_type_emplacement
 * @property Collection|ImageEmplacement[] $image_emplacements
 *
 * @package App\Models
 */
class Emplacement extends Eloquent
{
    use SoftDeletes;
    protected $table = 'emplacements';

    protected $casts = [
        'ref_type_emplacement_id' => 'int',
        'localite_id' => 'int',
        'ordre' => 'int'
    ];

    protected $fillable = [
        'ref_type_emplacement_id',
        'localite_id',
        'libelle',
        'libelle_ar',
        'description',
        'description_ar',
        'lat',
        'lng',
        'ordre'
    ];

    public function ref_type_emplacement()
    {
        return $this->belongsTo(RefTypeEmplacement::class);
    }
    public function localite()
    {
        return $this->belongsTo(Localite::class);
    }

    public function image_emplacements()
    {
        return $this->hasMany(ImageEmplacement::class);
    }
}
