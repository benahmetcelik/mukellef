<?php

namespace App\Http\Controllers\API2;

use App\Http\Controllers\Controller;
use App\Http\Requests\API2\User\SubscribeRequest;
use App\Http\Traits\HttpResponse;
use App\Interfaces\ISubscriptionV2Service;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;


class SubscriptionV2Controller extends Controller
{
    use HttpResponse;

    private ISubscriptionV2Service $subscriptionV2Service;

    public function __construct(ISubscriptionV2Service $subscriptionV2Service)
    {
        $this->subscriptionV2Service = $subscriptionV2Service;
    }

    public function subscribe(SubscribeRequest $request,Subscription $subscription):JsonResponse
    {
        $response = $this->subscriptionV2Service->subscribe($subscription,$request->user());
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }
}
