<?php

namespace App\Services;

use App\RefundTransaction;
use App\Exceptions\PaymentException;
use Maxpay\Scriney;

class RefundsHandler
{
    private $maxpay_helper;

    public function __construct()
    {
        $this->maxpay_helper = new Scriney(env('MAXPAY_PUBLIC_KEY'), env('MAXPAY_SECRET_KEY'));
    }


    public function refund($transaction)
    {
        if($transaction->refunded) {
            throw new PaymentException('Transaction has already been refunded');
        }

        $result = $this->maxpay_helper->refund($transaction->transaction_id);
        if(!$this->maxpay_helper->validateApiResult($result)) {
            throw new PaymentException('Cannot validate refund transaction from Maxpay!');
        }

        $refund_transaction = new RefundTransaction;
        $refund_transaction->refunded_transaction_id = $transaction->transaction_id;
        $refund_transaction->message = $result['message'];
        $refund_transaction->status = $result['status'];
        $refund_transaction->raw_response = $result;

        if($result['status'] == 'Success') {
            $refund_transaction->transaction_id = $result['transactionId'];
            $transaction->refunded = true;
            $transaction->save();
        }

        $refund_transaction->save();
        return $refund_transaction;
    }
}
