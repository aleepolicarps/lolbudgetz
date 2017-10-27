<?php

namespace App\Console\Commands;

use App\WebId;
use App\UserSubscription;
use App\Services\UserSubscriptionsHandler;
use Illuminate\Console\Command;

class RebillSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:rebill {count=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $count = $this->argument('count');
        echo "Rebilling first {$count} users ... \n";

        $user_subscriptions = UserSubscription::where('next_charge_date', '>=', date('Y-m-d H:i:s'))
            ->where('active', true)
            ->take($count)
            ->get();

        $total = count($user_subscriptions);
        $current_count = 1;
        foreach ($user_subscriptions as $user_subscription) {
            $user = $user_subscription->user()->first();
            $web_id = WebId::find($user_subscription->web_id);
            echo "{$current_count} out of {$total}: \t Charging {$user->name} with {$web_id->amount} ...\n";
            try {
                $updated_subscription = $this->user_subscription_handler->update_regular_subscription($user_subscription);

                if(!$updated_subscription->waitlist) {
                    echo("\t Rebill successful!\n");
                } else {
                    echo("\t Rebill unsuccessful! User moved to waitlist.\n");
                }
            } catch(Exception $e) {
                echo "Error encountered. Will skip that user. \n";
            }
            $current_count++;
        }
    }
}
