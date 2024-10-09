<?php

namespace App\Enums\Subscription;

enum CancelReasons: string
{
    case RENEW = 'renew';
    case FROM_USER = 'from_user';
    case FROM_ADMIN = 'from_admin';
    case STEP_LIMIT = 'step_limit';
    case PAYMENT_FAILED = 'payment_failed';
}
