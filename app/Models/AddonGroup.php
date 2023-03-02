<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\AddonGroupTranslation;
use Spatie\Permission\Models\Role;

class AddonGroup extends Model
{
    use Translatable;

    protected $table = 'addon_groups';

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
    public $translationForeignKey = 'addon_group_id';

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
    public $translationModel = AddonGroupTranslation::class;

    // function for filter records
    public function translation(){
        return $this->hasMany(AddonGroupTranslation::class, 'addon_group_id','id');
    }
}

