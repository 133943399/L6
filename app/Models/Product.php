<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mfgDate' => 'datetime',
        'expDate' => 'datetime',
    ];
}