<?php

namespace App\Interfaces;

use App\Http\Responses\ServiceResponse;
use App\Models\Subscription;

interface ISubscriptionService
{
    /**
     * Display a listing of the resource.
     * @param mixed $filters
     * @param int $perPage
     * @return ServiceResponse
     */
    public function index(mixed $filters,int $perPage): ServiceResponse;

    /**
     * Store a newly created resource in storage.
     * @param string $name
     * @param float $price
     * @param int $remaining_limit
     * @return ServiceResponse
     */
    public function store(string $name,float $price,int $remaining_limit): ServiceResponse;

    /**
    * Display the specified resource.
    * @param Subscription $subscription
    * @return ServiceResponse
    */
    public function show(Subscription $subscription): ServiceResponse;


    /**
     * Update the specified resource in storage.
     * @param Subscription $subscription
     * @param string $name
     * @param float $price
     * @param int $remaining_limit
     * @return ServiceResponse
     */
    public function update(Subscription $subscription,string $name,float $price,int $remaining_limit): ServiceResponse;


    /**
     * Remove the specified resource from storage.
     * @param Subscription $subscription
     * @return ServiceResponse
     */
    public function destroy(Subscription $subscription): ServiceResponse;
}
