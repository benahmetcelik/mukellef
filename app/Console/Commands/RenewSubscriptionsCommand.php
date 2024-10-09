<?php

namespace App\Console\Commands;

use App\Enums\Subscription\CancelReasons;
use App\Jobs\SendMailJob;
use App\Mail\SubscriptionCanNotRenewMail;
use App\Mail\SubscriptionRenewedMail;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Console\Command;

class RenewSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Abonelik süresi dolmuş kullanıcıların sürelerini yenileyerek ödemelerini alır';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userSubscriptions = UserSubscription::shouldBeRenew()->get();
        $this->info('Record Count ' . count($userSubscriptions));
        foreach ($userSubscriptions as $subscription) {
            /**
             * @var UserSubscription $subscription
             */
            $renewedSubscription = $subscription->renew();
            $paymentStatus = $subscription->tryPay(userSubscription: $renewedSubscription);
            $subscription->cancel(CancelReasons::RENEW);
            if ($paymentStatus) {
                $this->info('Payment success, Mail Sending. US-ID:' . $renewedSubscription->id);
                dispatch(new SendMailJob(
                    user: $subscription->getUser()->first(), mailable: new SubscriptionRenewedMail()
                ))
                    ->delay(now()->addMinute())
                    ->onQueue('notifications');
            } else {
                $this->info('Payment failed, Mail Sending. US-ID:' . $renewedSubscription->id);
                $renewedSubscription->cancel(CancelReasons::PAYMENT_FAILED);

                dispatch(new SendMailJob(
                    user: $subscription->getUser()->first(), mailable: new SubscriptionCanNotRenewMail()
                ))
                    ->delay(now()->addMinute())
                    ->onQueue('notifications');

            }

        }
    }

}
