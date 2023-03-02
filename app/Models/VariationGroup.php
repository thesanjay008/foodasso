<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\VariationGroupTranslation;
use Spatie\Permission\Models\Role;

class VariationGroup extends Model
{
    use Translatable;

    protected $table = 'variation_groups';

    protected $fillable = ['status'];

     /**
     * The localed attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['title','description'];

    /**
     * @var string
     */
    public $translationForeignKey = 'variation_group_id';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The class name for the localed model.
     *
     * @var string
     */
    public $translationModel = VariationGroupTranslation::class;

    // function for filter records
    public function translation(){
        return $this->hasMany(VariationGroupTranslation::class, 'variation_group_id','id');
    }
}

