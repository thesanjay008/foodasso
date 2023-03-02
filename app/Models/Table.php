<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\Translations\TableTranslation;

class Table extends Model
{
  use Translatable;
    protected $table = 'tables';
    protected $fillable = [
		'title',
		'table_number',
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
    public $translatedAttributes = ['title'];

    /**
     * @var string
     */
    public $translationForeignKey = 'table_id';

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
    public $translationModel = TableTranslation::class;

    // function for filter records
    public function translation(){
    	return $this->hasMany(TableTranslation::class,'table_id','id');
    }
}