<?php

use Illuminate\Http\Request;

Route::post('payment/failed', 'PaymentsController@handle_failed_payment');
