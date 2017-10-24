<?php

namespace App\Http\Controllers;

use App\Services\SaleTransactionsLogger;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    private $sale_transactions_logger;

    public function __construct()
    {
        $this->sale_transactions_logger = new SaleTransactionsLogger();
    }

    public function handle_failed_payment(Request $request)
    {
        $callback_params = $request->all();
        $sale_transaction = $this->sale_transactions_logger->save_from_callback($callback_params);
        return view('failed_signup');
    }
}
