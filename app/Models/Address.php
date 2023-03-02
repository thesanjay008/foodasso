<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $table = 'addresses';
    protected $fillable = ['country_id', 'city_id','address_type','address','postal_code','user_id'];

    /**
     * @return mixed
     */

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id' );
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id' );
    }
}
