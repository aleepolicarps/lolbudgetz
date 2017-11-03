<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefundTransaction extends Model
{
    protected $casts = [
        'raw_response' => 'array',
    ];

    public function is_successful()
    {
        return $this->status == 'Success';
    }
}
