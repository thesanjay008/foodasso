<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $table = 'wallet_history';
    
    protected $fillable = ['user_id', 'title', 'amount', 'balance', 'type', 'status'];

    // GET PRODUCT DETAILS
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
