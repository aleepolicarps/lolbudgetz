<?php

use Illuminate\Http\Request;

Route::post('payment/failed', 'PaymentsController@handle_failed_payment');
Route::post('payment/success', 'PaymentsController@handle_successful_payment');
