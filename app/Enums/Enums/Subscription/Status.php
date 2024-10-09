<?php

namespace App\Enums\Enums\Subscription;

enum Status:string
{
    case ACTIVE = 'active';
    case PASSIVE = 'passive';
    case CANCELLED = 'cancelled';

    case PAYMENT_WAITING = 'payment_waiting';
}
