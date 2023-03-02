<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuVariation extends Model
{

    protected $table = 'menu_variations';
    protected $fillable = ['menu_id', 'variation_id', 'price'];

    /**
     * @return mixed
     */

    public function menu()
    {
        return $this->belongsTo(Product::class, 'menu_id' );
    }

    public function variation()
    {
        return $this->belongsTo(AddonGroup::class, 'variation_id' );
    }
}
