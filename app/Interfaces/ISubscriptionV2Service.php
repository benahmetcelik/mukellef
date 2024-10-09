<?php

namespace App\Interfaces;

use App\Http\Responses\ServiceResponse;
use App\Models\User;

interface ISubscriptionV2Service
{
    /**
     * @param string $renewal_at
     * @param User $user
     * @return ServiceResponse
     */
    public function subscribe(string $renewal_at, User $user): ServiceResponse;
}
