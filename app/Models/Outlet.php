<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\OutletTranslation;

class Outlet extends Model
{
  use Translatable;
    protected $table = 'outlets';
    protected $fillable = [
		'user_id',
		'owner_id',
		'image',
		'rating',
        'banner_image',
		'phone_number',
		'email',
        'flat_discount',
		'start_time',
		'end_time',
		'latitude',
		'longitude',
        'zip_code',
		'country',
		'state',
		'city',
		'details',
		'delete_at',
		'status',
	];

    /**
     * @return mixed
     */
     /**
     * The localed attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['title','description','area','address'];

    /**
     * @var string
     */
    public $translationForeignKey = 'outlet_id';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The class name for the localed model.
     *
     * @var string
     */
    public $translationModel = OutletTranslation::class;

    // function for filter records
    public function translation(){
    	return $this->hasMany(OutletTranslation::class,'outlet_id','id');
    }

    //owner of Outlet
    public function owner(){
        return $this->hasOne(User::class,'id','owner_id');
    }
	
	//User Data
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function city(){
        return $this->hasOne(City::class,'id','city');
    }
}