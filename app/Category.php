<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'id', 'type', 'name'
    ];

    public function movement()
    {
        return $this->hasMany('App\Movement', 'category_id');
    }
    
}