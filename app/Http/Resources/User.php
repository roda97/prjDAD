<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        
        if ($this->wallet)
            $balance=$this->wallet->balance;
            $idwallet=$this->wallet->id;
        else
            $balance=null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'photo' => $this->photo,
            'nif' => $this->nif,
            'active' => $this->active,
            'balance' => $balance,
            'wallet_id' => $idwallet
                
        ];
    }
}
