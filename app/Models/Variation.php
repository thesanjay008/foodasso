<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\VariationTranslation;
use Spatie\Permission\Models\Role;

class Variation extends Model
{
    use Translatable;

    protected $table = 'variations';

    protected $fillable = ['status','group_id'];

     /**
     * The localed attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['title','description'];

    /**
     * @var string
     */
    public $translationForeignKey = 'variation_id';

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
    public $translationModel = VariationTranslation::class;

    // function for filter records
    public function translation(){
        return $this->hasMany(VariationTranslation::class, 'variation_id','id');
    }
}

