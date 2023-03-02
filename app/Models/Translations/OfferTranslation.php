<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offers_translations';


    public $translationModel = Offers::class;

    // function for filter records
    public function offer(){
        return $this->hasMany(Offers::class,'id','offer_id');
    }


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
    protected $fillable = ['offer_name','description'];
}
