<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class TableTranslation extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tables_translations';


    public $translationModel = Table::class;

    // function for filter records
    public function Table(){
        return $this->hasMany(Table::class,'id','table_id');
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
    protected $fillable = ['title'];
}
