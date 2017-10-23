<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function handle_failed_payment() {
        return 'failed';
    }
}
