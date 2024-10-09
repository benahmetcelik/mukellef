<?php

namespace App\Services\Payment\Providers;

use App\Models\Payment;

class StripeAdapter implements IPaymentAdapter
{

    /**
     * @param Payment $payment
     * @return bool
     */
    public function pay(Payment $payment): bool
    {
        $status = config('options.payment_status');
        $payment->status = $status;
        $payment->save();
        return $status;
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function payDirect(float $amount): bool
    {
        return config('options.payment_status');
    }
}
