<?php

namespace App\Services;

use App\WebId;
use App\SaleTransaction;
use App\UserSubscription;
use App\Enums\BillingPeriod;
use App\Mail\RebillFailed;
use App\Exceptions\SubscriptionException;
use Illuminate\Support\Facades\Mail;

class UserSubscriptionsHandler
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    private $rebill_transactions_handler;

    public function __construct(RebillTransactionsHandler $rebill_transactions_handler)
    {
        $this->rebill_transactions_handler = $rebill_transactions_handler;
    }

    public function start_user_trial($user)
    {
        $last_sale_transaction = SaleTransaction::where('uuid', $user->uuid)
            ->orderBy('created_at', 'DESC')
            ->first();

        $web_id = WebId::find($last_sale_transaction->web_id); // DO NOT BE CONFUSED. web_id is an object not an int
        $user_subscription = new UserSubscription;
        $user_subscription->user_id = $user->id;
        $user_subscription->web_id = $web_id->id;
        $user_subscription->trial = true;
        $user_subscription->active = true;
        $user_subscription->bill_token = $last_sale_transaction->bill_token;
        $user_subscription->waitlist = false;
        $user_subscription->last_charge_date = $last_sale_transaction->created_at;
        $user_subscription->next_charge_date = $this->compute_end_of_trial($user_subscription->last_charge_date, $web_id);
        $user_subscription->regular_charge_date = $this->compute_regular_charge_date($user_subscription->next_charge_date, $web_id);
        $user_subscription->save();

        return $user_subscription;
    }

    public function update_regular_subscription($user_subscription)
    {
        if($user_subscription->trial) {
            $user_subscription->trial = false;
            $user_subscription->end_trial_date = date(self::DATE_FORMAT);
        }

        $web_id = WebId::find($user_subscription->web_id); // DO NOT BE CONFUSED. web_id is an object not an int

        $rebill_transaction = $this->rebill_transactions_handler->rebill($user_subscription);
        $user_subscription->last_charge_date = $rebill_transaction->created_at;

        if($user_subscription->regular_charge_date < date(self::DATE_FORMAT)) {
            $last_regular_charge_date = $user_subscription->regular_charge_date;
            $user_subscription->regular_charge_date = $this->compute_regular_charge_date($last_regular_charge_date, $web_id);
        }

        if($rebill_transaction->is_successful()) {
            $user_subscription->waitlist = false;
            $user_subscription->next_charge_date = $user_subscription->regular_charge_date;
        } else {
            $user_subscription->waitlist = true;
            $user_subscription->next_charge_date = $this->compute_waitlist_next_charge_date($rebill_transaction->created_at);

            // NOTIFY USER ABOUT FAILED TRANSACTION
            $user = $user_subscription->user()->first();
            Mail::to($user->email)
                ->send(new RebillFailed());
        }

        $user_subscription->last_charge_date = $rebill_transaction->created_at;
        $user_subscription->save();

        return $user_subscription;
    }

    public function cancel($user_subscription, $ignore_delay = false)
    {
        $web_id = WebId::find($user_subscription->web_id);
        if(!$ignore_delay && $this->compute_allowed_usubscribe_date($user_subscription->created_at, $web_id) > date(self::DATE_FORMAT)) {
            throw new SubscriptionException("You cannot unsubscibe yet! Please wait {$web_id->unsubscribe_delay} hours after your subscription has started.");
        }

        if($user_subscription->trial) {
            $user_subscription->trial = false;
            $user_subscription->end_trial_date = date(self::DATE_FORMAT);
        }

        $user_subscription->active = false;
        $user_subscription->save();

        return $user_subscription;
    }

    public function compute_allowed_usubscribe_date($start_date, $web_id)
    {
        return date(self::DATE_FORMAT, strtotime($start_date . "+{$web_id->unsubscribe_delay} hours"));
    }

    private function compute_end_of_trial($start_date, $web_id)
    {
        switch($web_id->trial_period) {
            case BillingPeriod::THREE_DAYS: return date(self::DATE_FORMAT, strtotime($start_date . '+3 days'));
        }
    }

    private function compute_regular_charge_date($start_date, $web_id)
    {
        switch ($web_id->billing_period) {
            case BillingPeriod::ONE_MONTH: return date(self::DATE_FORMAT, strtotime($start_date . '+1 month'));
        }
    }

    private function compute_waitlist_next_charge_date($start_date)
    {
        return date(self::DATE_FORMAT, strtotime($start_date . '+15 days'));
    }
}
