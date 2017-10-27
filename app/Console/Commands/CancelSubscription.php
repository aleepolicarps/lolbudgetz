<?php

namespace App\Console\Commands;

use App\User;
use App\UserSubscription;
use Illuminate\Console\Command;
use App\Services\UserSubscriptionsHandler;
use App\Exceptions\SubscriptionException;

class CancelSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:cancel {email_address} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel a user\'s subscription.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $user_subscription_handler;

    public function __construct(UserSubscriptionsHandler $user_subscription_handler)
    {
        parent::__construct();
        $this->user_subscription_handler = $user_subscription_handler;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email_address = $this->argument('email_address');
        $force = $this->option('force');

        $user = User::where('email', $email_address)->first();

        if(!$email_address) {
            echo "User with email:{$email_address} does not exist.\n";
            return;
        }

        $subscription = UserSubscription::where('user_id', $user->id)->first();
        if(!$subscription || !$subscription->active) {
            echo "User {$user->name} does not have any active subscriptions.\n";
            return;
        }

        echo "Terminating {$user->name}'s subscription... \n";

        try{
            $this->user_subscription_handler->cancel($subscription, $force);
            echo "Subscription cancelled!\n";
        } catch(SubscriptionException $e) {
            echo $e->getMessage()."\n";
        }
    }
}
