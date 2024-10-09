<?php

namespace App\Services;

use App\Enums\Enums\Subscription\Status;
use App\Enums\Subscription\CancelReasons;
use App\Interfaces\IUserSubscriptionService;
use \App\Http\Responses\ServiceResponse;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Base\BaseService;
use App\Services\Payment\PaymentService;

class UserSubscriptionService extends BaseService implements IUserSubscriptionService
{

    /**
     * @param mixed $filters
     * @param int $userId
     * @param int $perPage
     * @return ServiceResponse
     */
    public function index(mixed $filters,int $userId, int $perPage): ServiceResponse
    {
        $model = UserSubscription::query();
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $model = $model->where($filter->column, $filter->operation, $filter->value);
            }
        }
        $model = $model->where('user_id',$userId)
        ->with('transactions')
        ;
        $model = $model->paginate($perPage);
        return new ServiceResponse(true, 'Successful', $model, 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Subscription $subscription, User $user, string $renewal_at): ServiceResponse
    {
        $userSubscription = new UserSubscription();
        $userSubscription->setSubscription($subscription);
        $userSubscription->renewal_at = $renewal_at;
        $userSubscription->setUser($user);
        $userSubscription->saveOrFail();
        $userSubscription->refresh();
        return new ServiceResponse(true, 'Successful', $userSubscription, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSubscription $userSubscription): ServiceResponse
    {
        return new ServiceResponse(true, 'Successful', $userSubscription, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserSubscription $userSubscription, string $renewal_at): ServiceResponse
    {
        $userSubscription->renewal_at = $renewal_at;
        $userSubscription->saveOrFail();
        return new ServiceResponse(true, 'Successful', $userSubscription, 200);
    }

    public function destroy(UserSubscription $userSubscription): ServiceResponse
    {
        $userSubscription->deleteOrFail();
        return new ServiceResponse(true, 'Successful', [], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(UserSubscription $userSubscription): ServiceResponse
    {
        $userSubscription->cancel(CancelReasons::FROM_USER);
        return new ServiceResponse(true, 'Successful', [], 200);
    }


    /**
     * @param Subscription $subscription
     * @param User $user
     * @return ServiceResponse
     */
    public function subscribe(Subscription $subscription, User $user):ServiceResponse
    {
        if (UserSubscription::byUser(user: $user)
            ->activeOrWaiting()
            ->first()){
            return new ServiceResponse(false, 'Already Exist', [], 422);
        }
        $userSubscription = new UserSubscription();
        $userSubscription->setUser($user);
        $userSubscription->setSubscription($subscription);
        $userSubscription->status = Status::PAYMENT_WAITING;
        $userSubscription->save();
        $userSubscription->refresh();
        if (config('options.subscribe_after_direct_pay') === true) {
            return $this->pay($user, $userSubscription);
        }
        return new ServiceResponse(true, 'Successful, Please Make Pay', $userSubscription, 200);
    }

    /**
     * @param User $user
     * @param UserSubscription $userSubscription
     * @return ServiceResponse
     */
    public function pay(User $user, UserSubscription $userSubscription):ServiceResponse
    {
        if ($userSubscription->isActive()){
            return new ServiceResponse(false, 'Already paid', [], 422);
        }
        $paymentService = new PaymentService();
        $paymentService->setProviderFromUser($user);
        $payment = $paymentService->createPaymentFromUserSubscription($userSubscription);
        $paymentService->setPayment($payment);
        $result = $paymentService->pay($payment);
        $message = $result ? 'Successfull' : 'Failed';
        $statusCode = $result ? 200 : 402;
        if (config('options.delete_failed_payments') === true
            && $result === false) {
            $payment->delete();
        }
        if ($result === true){
            $userSubscription->status = Status::ACTIVE;
            $userSubscription->save();

        }
        return new ServiceResponse(isSuccess: $result,
            message: $message,
            data: [],
            statusCode: $statusCode);
    }
}
