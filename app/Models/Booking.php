<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    
	protected $fillable = [
		'custom_order_id',
		'user_id',
		'token',
		'name',
		'email',
		'phone_number',
		'outlate_id',
		'table_id',
		'coupon_id',
		'offer_id',
		'quantity',
		'tax',
		'discount',
		'total',
		'grand_total',
		'date',
		'start_time',
		'end_time',
		'payment_method',
		'payment_method_id',
		'payment_tracking_id',
		'process_completed',
		'status'
	];
	
    // GET USER DETAILS
    public function user(){
        return $this->belongsTo(User::class, 'user_id' );
    }
}
