<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class OutletTranslation extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'outlets_translations';


    public $translationModel = Outlet::class;

    // function for filter records
    public function Outlet(){
        return $this->hasMany(Outlet::class,'id','outlet_id');
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
    protected $fillable = ['title','description','area','address'];
}
