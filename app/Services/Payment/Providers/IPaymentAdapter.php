<?php

namespace App\Services\Payment\Providers;


use App\Models\Payment;

interface IPaymentAdapter
{

    public function pay(Payment $payment):bool;
    public function payDirect(float $amount):bool;
}
