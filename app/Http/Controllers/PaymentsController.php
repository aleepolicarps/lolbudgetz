<?php

namespace App\Http\Controllers;

use App\Services\SaleTransactionsLogger;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    private $sale_transactions_logger;
    private $maxpay_helper;

    public function __construct()
    {
        $this->sale_transactions_logger = new SaleTransactionsLogger();
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
        // TODO: send invoice to email containing transaction details and signup link
        return view('successful_signup');
    }
}
