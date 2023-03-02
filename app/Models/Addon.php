<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\AddonTranslation;
use Spatie\Permission\Models\Role;

class Addon extends Model
{
    use Translatable;

    protected $table = 'addons';

    protected $guarded = [];

     /**
     * The localed attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['title','description'];

    /**
     * @var string
     */
    public $translationForeignKey = 'addon_id';

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
    public $translationModel = AddonTranslation::class;

    // function for filter records
    public function translation(){
        return $this->hasMany(AddonTranslation::class, 'addon_id','id');
    }
}

