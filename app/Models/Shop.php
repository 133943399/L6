<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //
    public function order()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function payment()
    {
        return $this->hasMany('App\Models\Payment');
    }
}
