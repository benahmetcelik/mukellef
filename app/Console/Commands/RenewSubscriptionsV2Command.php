<?php

namespace App\Console\Commands;

use App\Enums\Subscription\CancelReasons;
use App\Http\Responses\ServiceResponse;
use App\Interfaces\ISubscriptionV2Service;
use App\Jobs\SendMailJob;
use App\Mail\SubscriptionCanNotRenewMail;
use App\Mail\SubscriptionRenewedMail;
use App\Models\SubscriptionV2;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Payment\PaymentService;
use Illuminate\Console\Command;

class RenewSubscriptionsV2Command extends Command
{

    private ISubscriptionV2Service $subscriptionV2Service;

    public function __construct(ISubscriptionV2Service $subscriptionV2Service)
    {
        parent::__construct();
        $this->subscriptionV2Service = $subscriptionV2Service;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew-subscriptions:v2';

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
        $this->info('NOW: '.now()->toDateTimeString());
        $userSubscriptions = SubscriptionV2::shouldBeRenew()->get();
        $this->info('Record Count ' . count($userSubscriptions));
        foreach ($userSubscriptions as $subscription) {
            /**
             * @var SubscriptionV2 $subscription
             */
            $renewedSubscription = $subscription->renew();
            $user = $subscription->getUser()->first();
            $paymentService = new PaymentService();
            $paymentService->setProviderFromUser($user);
            $result = $paymentService->payDirect(100);
            if ($result){
                Transaction::create([
                    'user_id'=>$user->id,
                    'subscription_id'=>$renewedSubscription->id,
                    'price'=>100
                ]);
                $this->info('Payment success, Mail Sending. US-ID:' . $renewedSubscription->id);
                dispatch(new SendMailJob(
                    user: $subscription->getUser()->first(), mailable: new SubscriptionRenewedMail()
                ))
                    ->delay(now()->addMinute())
                    ->onQueue('notifications');
            }else{
                $this->info('Payment failed, Mail Sending. US-ID:' . $renewedSubscription->id);
                $renewedSubscription->delete();
                dispatch(new SendMailJob(
                    user: $subscription->getUser()->first(), mailable: new SubscriptionCanNotRenewMail()
                ))
                    ->delay(now()->addMinute())
                    ->onQueue('notifications');
            }
        }
    }

}
