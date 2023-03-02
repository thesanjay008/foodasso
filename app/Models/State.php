<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';

    protected $fillable = ['country_id','name','status'];

    /**
     * @return mixed
     */
    public function country(){
        return $this->belongsTo(Country::class, 'country_id' );
    }

    public function city(){
        return $this->hasMany('App\Models\City','state_id','id' );
    }
}
