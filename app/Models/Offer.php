<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $fillable = ['title','code','image','description','discount_type','discount','start_date','end_date','status'];
}