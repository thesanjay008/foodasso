<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'template_master';

    protected $fillable = [
    	'name','preview','html_value','status'
    ];

}
