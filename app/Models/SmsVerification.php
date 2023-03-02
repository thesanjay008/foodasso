<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsVerification extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_verifications';

    protected $fillable = ['phone_number','code','status'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
   
}
