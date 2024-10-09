<?php

namespace App\Services;

use \App\Http\Responses\ServiceResponse;
use App\Interfaces\ISubscriptionV2Service;
use App\Models\SubscriptionV2;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Payment\PaymentService;

class SubscriptionV2Service implements ISubscriptionV2Service
{
    /**
     * @param string $renewal_at
     * @param User $user
     * @return ServiceResponse
     */
    public function subscribe(string $renewal_at,User $user):ServiceResponse
    {
        $userSubscription = SubscriptionV2::create([
            'user_id'=>$user->id,
            'renewal_at'=>$renewal_at
        ]);
        return new ServiceResponse(true, 'Successful', $userSubscription, 200);
    }

    /**
     * @param SubscriptionV2 $subscriptionV2
     * @param string $renewal_at
     * @param User $user
     * @return ServiceResponse
     */
    public function update(SubscriptionV2 $subscriptionV2,string $renewal_at,User $user):ServiceResponse
    {
        $subscriptionV2->user_id = $user->id;
        $subscriptionV2->renewal_at = $renewal_at;
        $subscriptionV2->save();
        return new ServiceResponse(true, 'Successful', $subscriptionV2, 200);
    }

    /**
     * @param SubscriptionV2 $subscriptionV2
     * @return ServiceResponse
     */
    public function delete(SubscriptionV2 $subscriptionV2):ServiceResponse
    {
        //Burada sadece kendi aboneliğini silmek istenmemiş
        $subscriptionV2->delete();
        return new ServiceResponse(true, 'Successful', [], 200);
    }

    /**
     * @param User $user
     * @param int $subscription_id
     * @param float $price
     * @return ServiceResponse
     */
    public function transaction(User $user,int $subscription_id,float $price):ServiceResponse
    {
        $paymentService = new PaymentService();
        $paymentService->setProviderFromUser($user);
        $result = $paymentService->payDirect($price);
        if (!$result){
            return new ServiceResponse(false, 'Failed', [], 402);
        }
        $transaction = Transaction::create([
            'user_id'=>$user->id,
            'subscription_id'=>$subscription_id,
            'price'=>$price
        ]);
        return new ServiceResponse(true, 'Successful', $transaction, 200);
    }

    /**
     * @param User $user
     * @return ServiceResponse
     */
    public function index(User $user): ServiceResponse
    {
        $model = $user->load('subscriptionsV2.transactions');
        return new ServiceResponse(true, 'Successful', $model, 200);
    }
}
