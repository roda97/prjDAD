<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Movement extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->transferWallet)){
            $email=$this->transferWallet->email;
            //$wallet=$this->transferWallet;
            $user_photo=$this->transferWallet->user->photo;
        }
        else{
            $email=null;
            //$wallet=null;
            $user_photo=null;
        }
        if (isset($this->category))
            $category_name=$this->category->name;
        else
            $category_name=null;
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'type' => $this->type,
            'transfer' => $this->transfer,
            'transfer_movement_id' => $this->transfer_movement_id,
            'transfer_wallet_id' => $this->transfer_wallet_id, //? $this->transfer_wallet_id : null,
            'type_payment' => $this->type_payment,
            'category_id' => $this->category_id, //? $this->category_id : null,
            'iban' => $this->iban,
            'mb_entity_code' => $this->mb_entity_code,
            'mb_payment_reference' => $this->mb_payment_reference,
            'description' => $this->description,
            'source_description' => $this->source_description,
            'date' => $this->date,
            'start_balance' => $this->start_balance,
            'end_balance' => $this->end_balance,
            'value' => $this->value,
            'email' => $email,
            'category_name' => $category_name,
            'user_photo' => $user_photo,
            'emailIncome' => (isset($this->creditWallet))?$this->creditWallet->email:''
            //'wallet' => $wallet,
        ];
    }
}
