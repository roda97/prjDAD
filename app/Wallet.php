<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'email', 'balance'
    ];


    /*public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }*/

    public function user()
    {
        return $this->belongsTo(User::class, 'id');//, 'id');
    }

    public function movements()
    {
        return $this->hasMany('App\Movement', 'wallet_id');
    }

   /* public function movementTransfer()
    {
        return $this->hasMany('App\Movement', 'transfer_wallet_id');
    }*/

    public function transferMovement()
    {
        return $this->belongsToMany(Movement::class, 'transfer_wallet_id', 'id');
    }

    public function creditMovement()
    {
        return $this->belongsToMany(Movement::class, 'wallet_id', 'id');
    }


}