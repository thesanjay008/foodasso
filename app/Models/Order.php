<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
		'custom_order_id','user_id','contact_person','contact_number','address_id','address',
		'table_id','coupon_id',
		'item_count','quantity','tax','discount','delivery_fee','total','grand_total',
		'shipping_date','shipping_address','tracking_id','delivery_date',
		'payment_mode','payment_method_id','payment_method','payment_order_id',
		'order_type','order_status','goods_received','order_date','status'
	];

    // Get User detail
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    // Get address detail
    public function address(){
        return $this->belongsTo(Address::class,'address_id');
    }

    // Get order items
    public function order_items(){
        return $this->hasMany(OrderItem::class, 'order_id','id');
    }

    // Get payment detail
    public function payment(){
        return $this->belongsTo(Payment::class,'address_id');
    }

    // Get coupon detail
    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id');
    }
}