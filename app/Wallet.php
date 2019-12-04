<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'id', 'email', 'balance'
    ];


public function user()
{
    return $this->belongsTo('App\User', 'id');
}

public function movementid()
{
    return $this->hasMany('App\Movement', 'wallet_id');
}

public function movementtransfer()
{
    return $this->hasMany('App\Movement', 'transfer_wallet_id');
}

}