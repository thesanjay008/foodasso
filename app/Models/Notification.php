<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
	
    protected $fillable = [
  		'title','message','type','type_id','relation_id','user_id','extra','is_sent','is_read'
    ];

    	public function user(){
        return $this->belongsTo(User::class, 'user_id' );
    }

     	public function doctor(){
        //return $this->belongsTo(Doctor::class,'owner_id');
    }

}
