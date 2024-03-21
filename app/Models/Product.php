<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
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
