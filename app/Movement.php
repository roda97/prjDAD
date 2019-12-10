<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'type','transfer','type_payment','iban',
        'mb_entity_code', 'mb_payment_reference','description', 'source_description',
        'date', 'start_balance','end_balance','value',
    ];

    public function wallet()
    {
        return $this->belongsTo('App\Wallet', 'wallet_id', 'id');
    }

    /*public function transferWallet()
    {
        return $this->belongsTo('App\Wallet', 'id', 'transfer_wallet_id');
    }*/

    public function transferWallet() //atenção ao create credit!
    {
        return $this->hasOne(Wallet::class,  'id', 'transfer_wallet_id');
    }

    
    /*public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }*/

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function movementstransfer()
    {
        return $this->hasone('App\Wallet', 'transfer_movement_id');
    }

    public function transfermovements()
    {
        return $this->hasone('App\Wallet', 'transfer_movement_id', 'id');
    }
    

}
