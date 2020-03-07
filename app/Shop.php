<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
