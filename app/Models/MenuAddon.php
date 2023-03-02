<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuAddon extends Model
{

    protected $table = 'menu_addons';
    protected $fillable = ['menu_id', 'addon_group_id'];

    /**
     * @return mixed
     */

    public function menu()
    {
        return $this->belongsTo(Product::class, 'menu_id' );
    }

    public function addon_group()
    {
        return $this->belongsTo(AddonGroup::class, 'addon_group_id' );
    }
}
