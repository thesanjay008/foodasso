<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';
    
    protected $fillable = ['state_id', 'title','status'];

    /**
     * @return mixed
     */

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id' );
    }
}
