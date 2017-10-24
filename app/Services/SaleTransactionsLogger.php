<?php

namespace App\Services;

use App\WebId;
use App\SaleTransaction;

class SaleTransactionsLogger
{
    public function save_from_callback($callback_params)
    {
        $sale_transaction = new SaleTransaction;
        $sale_transaction->transaction_id = $callback_params['transactionId'];
        $sale_transaction->uuid = $callback_params['uniqueUserId'];
        $sale_transaction->total_amount = $callback_params['totalAmount'];
        $sale_transaction->converted_amount = $callback_params['convertedAmount'];
        $sale_transaction->currency = $callback_params['currency'];
        $sale_transaction->transaction_type = $callback_params['transactionType'];
        $sale_transaction->status = $callback_params['status'];
        $sale_transaction->message = $callback_params['message'];
        $sale_transaction->code = $callback_params['code'];
        $sale_transaction->bill_token = $callback_params['billToken'];
        $web_id = WebId::where('public_id', $callback_params['customParameters']['custom_webid'])->first();
        $sale_transaction->web_id = $web_id->id;
        $sale_transaction->raw_response = $callback_params;

        $sale_transaction->save();
        return $sale_transaction;
    }
}
