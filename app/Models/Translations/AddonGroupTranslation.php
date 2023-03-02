<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class AddonGroupTranslation extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addon_group_translations';


    public $translationModel = AddonGroup::class;


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
