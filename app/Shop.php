<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //
    public function order()
    {
        return $this->hasMany('App\Order');
    }

    public function payment()
    {
        return $this->hasMany('App\Payment');
    }
}
