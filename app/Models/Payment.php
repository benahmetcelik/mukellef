<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * @param UserSubscription $userSubscription
     * @return void
     */
    public function setUserSubscription(UserSubscription $userSubscription)
    {
        $this->user_subscription_id = $userSubscription->id;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user_id = $user->id;
    }
}
