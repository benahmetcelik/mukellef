<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Payment\Providers\IPaymentAdapter;
use App\Services\Payment\Providers\IyzicoAdapter;
use App\Services\Payment\Providers\StripeAdapter;

class PaymentService implements IPaymentService
{

    private IPaymentAdapter $provider;

    private User $user;

    private ?Payment $payment = null;


    /**
     * @param Payment $payment
     * @return void
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param Payment $payment
     * @return bool
     */
    public function pay(Payment $payment): bool
    {
        return $this->provider->pay($this->payment);
    }

    public function payDirect($amount):bool
    {
        return $this->provider->payDirect($amount);
    }

    /**
     * @param string $provider
     * @return IPaymentService
     */
    public function setProvider(string $provider): IPaymentService
    {
        $this->provider = match ($provider) {
            'iyzico' => new IyzicoAdapter(),
            default => new StripeAdapter()
        };
        return $this;
    }

    /**
     * @return IPaymentAdapter
     */
    public function getProvider(): IPaymentAdapter
    {
        return $this->provider;
    }

    /**
     * @param User $user
     * @return IPaymentService
     */
    public function setProviderFromUser(User $user): IPaymentService
    {
        $this->setUser($user);
        $this->setProvider($user->payment_provider);
        return $this;
    }

    /**
     * @param UserSubscription $userSubscription
     * @return Payment
     */
    public function createPaymentFromUserSubscription(UserSubscription $userSubscription): Payment
    {
        $payment = new Payment();
        $payment->amount = $userSubscription->getSubscription->price;
        $payment->setUserSubscription($userSubscription);
        $payment->setUser($userSubscription->getUser);
        $payment->save();
        $payment->refresh();
        return $payment;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
