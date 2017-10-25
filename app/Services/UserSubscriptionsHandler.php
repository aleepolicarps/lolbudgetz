<?php

namespace App\Services;

use App\WebId;
use App\SaleTransaction;
use App\UserSubscription;
use App\Enums\BillingPeriod;

class UserSubscriptionsHandler
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    public function start_user_trial($user)
    {
        $last_sale_transaction = SaleTransaction::where('uuid', $user->uuid)
            ->orderBy('created_at', 'DESC')
            ->first();

        $web_id = WebId::find($last_sale_transaction->web_id ); // DO NOT BE CONFUSED. web_id is an object not an int

        $user_subscription = new UserSubscription;
        $user_subscription->user_id = $user->id;
        $user_subscription->web_id = $web_id->id;
        $user_subscription->trial = true;
        $user_subscription->active = true;
        $user_subscription->bill_token = $last_sale_transaction->bill_token;
        $user_subscription->waitlist = false;
        $user_subscription->last_charge_date = $last_sale_transaction->created_at;
        $user_subscription->next_charge_date = $this->compute_end_of_trial(date(self::DATE_FORMAT), $web_id);
        $user_subscription->save();
    }

    private function compute_end_of_trial($start_date, $web_id)
    {
        switch($web_id->trial_period) {
            case BillingPeriod::THREE_DAYS: return date(self::DATE_FORMAT, strtotime($start_date . '+3 days'));
        }
    }
}
