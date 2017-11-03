<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    protected $casts = [
        'raw_response' => 'array',
        'refunded' => 'boolean'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function is_successful()
    {
        return $this->status == 'success';
    }
}
