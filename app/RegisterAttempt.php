<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterAttempt extends Model
{
    public function getName() {
        return $this->first_name.' '.$this->last_name;
    }
}
