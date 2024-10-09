<?php

namespace App\Models;

use App\Enums\Enums\Subscription\Status;
use App\Enums\Subscription\CancelReasons;
use App\Services\Payment\PaymentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use \Illuminate\Contracts\Database\Query\Builder;

/**
 * @method shouldBeRenew();
 * @method byUser(User $user);
 * @method active();
 * @method waiting();
 * @method activeOrWaiting();
 */
class UserSubscription extends Model
{
    use HasFactory;

    protected $casts = [
      'status'=>Status::class
    ];

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user_id = $user->id;
    }

    /**
     * @param Subscription $subscription
     * @return void
     */
    public function setSubscription(Subscription $subscription): void
    {
        $this->subscription_id = $subscription->id;
        $this->remaining_step = $subscription->remaining_limit;
        $this->renewal_at = now()->addDays($subscription->period);
    }

    /**
     * @return HasOne
     */
    public function getSubscription():HasOne
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeShouldBeRenew(Builder $builder):Builder
    {
       return  $builder->where('remaining_step', '>=', 1)
            ->where('renewal_at', '<=',now()->toDateTimeString())
        ->where('status','=',Status::ACTIVE);

    }

    /**
     * @param Builder $builder
     * @param User $user
     * @return Builder
     */
    public function scopeByUser(Builder $builder,User $user):Builder
    {
        return $builder->where('user_id',$user->id);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder):Builder
    {
        return $builder->where('status',Status::ACTIVE);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWaiting(Builder $builder):Builder
    {
        return $builder->where('status',Status::PAYMENT_WAITING);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActiveOrWaiting(Builder $builder):Builder
    {
        return $builder->where(function ($query){
            $query->where('status',Status::ACTIVE)
                ->orWhere('status',Status::PAYMENT_WAITING);
        });
    }

    /**
     * @return HasOne
     */
    public function getUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    /**
     * @param UserSubscription $userSubscription
     * @return bool
     */
    public function tryPay(UserSubscription $userSubscription): bool
    {
        $user = $this->getUser()->first();
        $paymentService = new PaymentService();
        $paymentService->setProviderFromUser($user);
        $payment = $paymentService->createPaymentFromUserSubscription($userSubscription);
        $paymentService->setPayment($payment);
        $result = $paymentService->pay(payment: $payment);
        if (config('options.delete_failed_payments') === true &&
        !$result) {
            $payment->delete();
        }
        return $result;
    }

    /**
     * @param CancelReasons $cancelReason
     * @return void
     */
    public function cancel(CancelReasons $cancelReason):void
    {
        $this->status = Status::CANCELLED;
        $this->cancel_reason = $cancelReason;
        $this->save();
    }

    /**
     * @return UserSubscription
     */
    public function renew():UserSubscription
    {
        $userSubscription = new UserSubscription();
        $userSubscription->setUser($this->getUser()->first());
        $userSubscription->setSubscription($this->getSubscription()->first());
        $userSubscription->remaining_step = max($this->remaining_step - 1,0);
        $userSubscription->save();
        $userSubscription->refresh();
        return $userSubscription;
    }

    /**
     * @return bool
     */
    public function isActive():bool
    {
        return Status::ACTIVE === $this->status;
    }

    /**
     * @return HasMany
     */
    public function transactions():HasMany
    {
        return $this->hasMany(Payment::class,'user_subscription_id','id');
    }
}
