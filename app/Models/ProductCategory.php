<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    protected $fillable = ['product_id', 'category_id','owner_id','owner_type'];
    /**
     * @return mixed
     */

    // protected $with = ['category'];
    
    public function category(){
    	return $this->hasMany(Category::class, 'category_id','id');
    }

    // GET CATEGORIES
    public function categories(){
      return $this->belongsTo(Category::class,'category_id');
    }

}
