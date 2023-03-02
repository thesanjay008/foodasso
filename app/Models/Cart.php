<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    
    protected $fillable = ['user_id','token','product_id','price','quantity','title','total','date','order_type','table_id'];

    // GET PRODUCT DETAILS
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}
