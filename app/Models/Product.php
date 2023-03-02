<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\ProductTranslation;

class Product extends Model
{
    use Translatable;

     protected $table = 'products';

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
    public $translationForeignKey = 'product_id';

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
    public $translationModel = ProductTranslation::class;

    // function for filter records
    public function translation(){
    	return $this->hasMany(ProductTranslation::class,'product_id','id');
    }

    public function vendor(){
		return $this->belongsTo(User::class, 'user_id', 'id');
    }
	
	public function restaurant(){
		return $this->belongsTo(Restaurant::class, 'owner_id', 'id');
    }

    // public function country(){
    //     return $this->belongsTo(Country::class, 'country_id' );
    // }
}
