<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
        'last_charge_date',
        'next_charge_date',
        'end_trial_date',
        'regular_charge_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
