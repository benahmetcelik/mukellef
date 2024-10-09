<?php

namespace App\Services;

use App\Interfaces\ISubscriptionService;
use App\Http\Responses\ServiceResponse;
use App\Models\Subscription;
use App\Models\SubscriptionV2;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Base\BaseService;

class SubscriptionService extends BaseService implements ISubscriptionService
{

    /**
     * @param mixed $filters
     * @param int $perPage
     * @return ServiceResponse
     */
    public function index(mixed $filters, int $perPage): ServiceResponse
    {
        $model = Subscription::query();
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $model = $model->where($filter->column, $filter->operation, $filter->value);
            }
        }
        $model = $model->paginate($perPage);
        return new ServiceResponse(true, 'Successful', $model, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param string $name
     * @param float $price
     * @param int $remaining_limit
     * @param int $period
     * @return ServiceResponse
     */
    public function store(string $name, float $price, int $remaining_limit, int $period): ServiceResponse
    {
        return new ServiceResponse(true, 'Successful', Subscription::create([
            'name' => $name,
            'price' => $price,
            'remaining_limit' => $remaining_limit,
            'period' => $period
        ]), 200);
    }

    /**
     * Display the specified resource.
     * @param Subscription $subscription
     * @return ServiceResponse
     */
    public function show(Subscription $subscription): ServiceResponse
    {
        return new ServiceResponse(true, 'Successful', $subscription, 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Subscription $subscription
     * @param string $name
     * @param float $price
     * @param int $remaining_limit
     * @param int $period
     * @return ServiceResponse
     */
    public function update(Subscription $subscription, string $name, float $price, int $remaining_limit, int $period): ServiceResponse
    {
        $subscription->name = $name;
        $subscription->price = $price;
        $subscription->remaining_limit = $remaining_limit;
        $subscription->period = $period;
        $subscription->save();
        return new ServiceResponse(true, 'Successful', $subscription, 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Subscription $subscription
     * @return ServiceResponse
     */
    public function destroy(Subscription $subscription): ServiceResponse
    {
        $subscription->delete();
        return new ServiceResponse(true, 'Successful', [], 200);
    }
}
