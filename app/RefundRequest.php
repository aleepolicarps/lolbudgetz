<?php

namespace App;

use App\Enums\RefundRequestStatus;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function is_pending()
    {
        return $this->status == RefundRequestStatus::PENDING;
    }

    public function get_status_as_string()
    {
        return RefundRequestStatus::to_string($this->status);
    }
}
