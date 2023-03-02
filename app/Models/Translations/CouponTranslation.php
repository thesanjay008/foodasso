<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class CouponTranslation extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupon_translations';


    public $translationModel = Coupon::class;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];
}
