<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\CategoryTranslation;
use Spatie\Permission\Models\Role;

class Category extends Model
{
    use Translatable;

    protected $table = 'categories';

    protected $fillable = ['modules_type','image','status'];

     /**
     * The localed attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['title','description'];

    /**
     * @var string
     */
    public $translationForeignKey = 'cat_id';

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
    public $translationModel = CategoryTranslation::class;

    // function for filter records
    public function translation(){
    	return $this->hasMany(CategoryTranslation::class, 'cat_id','id');
    }
}

