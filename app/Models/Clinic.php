<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\ClinicTranslation;

class Clinic extends Model
{
    use Translatable;

    protected $table = 'clinics';

    protected $fillable = ['phone_number','image','start_time','end_time','status','email_address','address','lattitude','longitude','avg_rating','total_rating','select_type','hospital_id','user_id'];

     /**
     * The localed attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = ['clinic_name','description'];

    /**
     * @var string
     */
    public $translationForeignKey = 'clinic_id';

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
    public $translationModel = ClinicTranslation::class;

    // function for filter records
    public function translation(){
    	return $this->hasMany(ClinicTranslation::class, 'clinic_id','id');
    }

    // Department
    public function department(){
        return $this->hasMany(ClinicDepartment::class, 'clinic_id','id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id' );
    }
}
