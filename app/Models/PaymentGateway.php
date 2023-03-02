<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class PaymentGateway extends Model
{
    protected $table = 'payment_gateways';
	protected $fillable = ['title', 'slug', 'image', 'icon', 'status', 'delete_at'];
}