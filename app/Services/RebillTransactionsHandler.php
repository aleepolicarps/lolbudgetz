<?php

namespace App\Services;

use App\RebillTransaction;
use App\WebId;
use App\Exceptions\PaymentException;
use Maxpay\Scriney;
use Maxpay\Lib\Model\UserInfo;

class RebillTransactionsHandler
{
    private $maxpay_helper;

    public function __construct()
    {
        $this->maxpay_helper = new Scriney(env('MAXPAY_PUBLIC_KEY'), env('MAXPAY_SECRET_KEY'));
    }

    public function rebill($user_subscription)
    {
        $user = $user_subscription->user()->first();
        $web_id = WebId::find($user_subscription->web_id);

        try {
            $result = $this->maxpay_helper->createRebillRequest($user_subscription->bill_token, $user->uuid)
                ->setProductId($web_id->product_id)
                ->setUserInfo(new UserInfo($user->email, $user->first_name, $user->last_name))
                ->send();
        } catch (\Maxpay\Lib\Exception\GeneralMaxpayException $e) {
            throw new PaymentException('Unknown error occurred');
        }

        if (!$this->maxpay_helper->validateApiResult($result)) {
            throw new PaymentException('Cannot validate rebilling from Maxpay');
        }
        $result['web_id'] = $web_id->id;
        return $this->save_results($result);
    }

    private function save_results($result)
    {
        $rebill_transaction = new RebillTransaction;
        $rebill_transaction->transaction_id = $result['transactionId'];
        $rebill_transaction->reference = isset($result['reference']) ? $result['reference'] : null;
        $rebill_transaction->uuid = $result['uniqueUserId'];
        $rebill_transaction->total_amount = $result['totalAmount'];
        $rebill_transaction->currency = $result['currency'];
        $rebill_transaction->transaction_type = $result['transactionType'];
        $rebill_transaction->status = $result['status'];
        $rebill_transaction->message = $result['message'];
        $rebill_transaction->code = $result['code'];
        $rebill_transaction->web_id = $result['web_id'];
        $rebill_transaction->raw_response = $result;
        $rebill_transaction->save();
        return $rebill_transaction;
    }
}
