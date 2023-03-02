<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
  		'owner_id','owner_type','user_id','rating'
    ];

    	public function user(){
        return $this->belongsTo(User::class, 'user_id' );
    }

     	public function doctor(){
        return $this->belongsTo(Doctor::class,'owner_id');
    }

        public function nurse(){
        return $this->belongsTo(Nurse::class,'owner_id');
    }

}
