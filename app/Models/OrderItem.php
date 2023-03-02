<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';    
    protected $fillable = ['order_id','custom_order_id','product_id','title','quantity','price','total'];

     public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
