<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
	protected $table = 'areas';
    protected $fillable = ['title', 'city_id','postal_code', 'latitude', 'longitude', 'delivery_charges', 'status'];

    /**
     * @return mixed
     */

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
