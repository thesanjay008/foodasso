<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class VariationTranslation extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'variation_translations';


    public $translationModel = Variation::class;

    // function for filter records
    public function category(){
        return $this->hasMany(Variation::class,'id','cat_id');
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
    protected $fillable = ['title','description'];
}
