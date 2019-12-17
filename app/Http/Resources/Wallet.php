<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Wallet extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        /*if ($this->user)
            $users=$this->user;
        else
            $users=null;*/
        return [
            'id' => $this->id,
            'email' => $this->email,
            'balance' => $this->balance,
            'user_name' => $this->user->name,
        ];
    }
}