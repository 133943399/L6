<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;
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
