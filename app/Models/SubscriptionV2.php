<?php

namespace App\Models;

use App\Enums\Enums\Subscription\Status;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubscriptionV2 extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'renewal_at'
    ];

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'subscription_id', 'id');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeShouldBeRenew(Builder $builder):Builder
    {
        $now = now();
        $startOfMinute = $now->copy()->startOfMinute();
        $endOfMinute = $now->copy()->endOfMinute();

        return $builder->whereBetween('renewal_at', [$startOfMinute, $endOfMinute]);
    }

    /**
     * @return SubscriptionV2
     */
    public function renew():SubscriptionV2
    {
        $userSubscription = new SubscriptionV2();
        $userSubscription->user_id = $this->getUser()->first()->id;
        $userSubscription->renewal_at = now()->addMonth();
        $userSubscription->save();
        $userSubscription->refresh();
        return $userSubscription;
    }

    /**
     * @return HasOne
     */
    public function getUser():HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
