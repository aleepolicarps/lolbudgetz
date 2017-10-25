<?php

namespace App\Http\Controllers;

use App\Mail\SignupSuccessful;
use App\RegisterAttempt;
use App\Services\SaleTransactionsLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentsController extends Controller
{
    private $sale_transactions_logger;
    private $maxpay_helper;

    public function __construct(SaleTransactionsLogger $sale_transactions_logger)
    {
        $this->sale_transactions_logger = $sale_transactions_logger;
        $this->maxpay_helper = new \Maxpay\Scriney(env('MAXPAY_PUBLIC_KEY'), env('MAXPAY_SECRET_KEY'));
    }

    public function handle_failed_payment(Request $request)
    {
        $callback_params = $request->all();
        if(!$this->maxpay_helper->validateCallback($callback_params)) {
            abort(500);
        }

        $sale_transaction = $this->sale_transactions_logger->save_from_callback($callback_params);
        return view('failed_signup');
    }

    public function handle_successful_payment(Request $request)
    {
        $callback_params = $request->all();
        if(!$this->maxpay_helper->validateCallback($callback_params)) {
            abort(500);
        }

        $sale_transaction = $this->sale_transactions_logger->save_from_callback($callback_params);
        $register_attempt = RegisterAttempt::where('uuid', $sale_transaction->uuid)->first();
        Mail::to($register_attempt->email_address)
            ->send(new SignupSuccessful($register_attempt));

        return view('successful_signup');
    }
}
