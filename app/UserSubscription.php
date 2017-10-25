<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{

        protected $dates = [
            'created_at',
            'updated_at',
            'last_charge_date',
            'next_charge_date'
        ];
}
