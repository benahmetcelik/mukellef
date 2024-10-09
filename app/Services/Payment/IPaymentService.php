<?php

namespace App\Services\Payment;

use App\Services\Payment\Providers\IPaymentAdapter;

interface IPaymentService extends IPaymentAdapter
{

    public function setProvider(string $provider):self;

    public function getProvider():IPaymentAdapter;


}
