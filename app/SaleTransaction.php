<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    protected $casts = [
        'raw_response' => 'array'
    ];
}
