<?php

namespace App\Http\Controllers;

use App\Exceptions\SubscriptionException;
use App\UserSubscription;
use App\Services\UserSubscriptionsHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    private $user_subscription_handler;

    public function __construct(UserSubscriptionsHandler $user_subscription_handler)
    {
        $this->user_subscription_handler = $user_subscription_handler;
    }

    public function unsubscribe()
    {
        $user = Auth::user();
        if(!$user) {
            abort(401, 'You are unauthorized to use this feature!');
        }

        try {
            $user_subscription = UserSubscription::where('user_id', $user->id)->first();
            $this->user_subscription_handler->cancel($user_subscription);
            return response()->json(['status' => 'success']);
        } catch(SubscriptionException $e) {
            abort(400, $e->getMessage());
        }
    }
}
