<?php

namespace App\Interfaces;

use App\Http\Responses\ServiceResponse;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;

interface IUserSubscriptionService
{

    /**
     * Display a listing of the resource.
     * @param mixed $filters
     * @param int $userId
     * @param int $perPage
     * @return ServiceResponse
     */
    public function index(mixed $filters, int $userId, int $perPage): ServiceResponse;

    /**
     * Store a newly created resource in storage.
     * @param Subscription $subscription
     * @param User $user
     * @param string $renewal_at
     * @return ServiceResponse
     */
    public function store(Subscription $subscription,User $user,string $renewal_at): ServiceResponse;

    /**
     * Display the specified resource.
     * @param UserSubscription $userSubscription
     * @return ServiceResponse
     */
    public function show(UserSubscription $userSubscription): ServiceResponse;

    /**
     * Update the specified resource in storage.
     * @param UserSubscription $userSubscription
     * @param string $renewal_at
     * @return ServiceResponse
     */
    public function update(UserSubscription $userSubscription,string $renewal_at): ServiceResponse;


    /**
     * Remove the specified resource from storage.
     * @param UserSubscription $userSubscription
     * @return ServiceResponse
     */
    public function destroy(UserSubscription $userSubscription): ServiceResponse;
}
