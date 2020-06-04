<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'orderDate' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }
}
