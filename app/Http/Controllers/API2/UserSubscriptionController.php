<?php

namespace App\Http\Controllers\API2;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSubscription\CancelRequest;
use App\Http\Requests\UserSubscription\IndexRequest;
use App\Http\Requests\UserSubscription\PayRequest;
use App\Http\Requests\UserSubscription\SubscribeRequest;
use App\Http\Traits\HttpResponse;
use App\Interfaces\IUserSubscriptionService;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;


class UserSubscriptionController extends Controller
{
    use HttpResponse;

    private IUserSubscriptionService $userSubscriptionService;

    public function __construct(IUserSubscriptionService $userSubscriptionService)
    {
        $this->userSubscriptionService = $userSubscriptionService;
    }

    /**
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function index(IndexRequest $request):JsonResponse
    {
        $filters = $request->filters;
        $perPage = $request->per_page ?? 10;
        $response = $this->userSubscriptionService->index($filters,$request->user()->id,$perPage);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param SubscribeRequest $request
     * @param Subscription $subscription
     * @return JsonResponse
     */
    public function subscribe(SubscribeRequest $request,Subscription $subscription):JsonResponse
    {
        $response = $this->userSubscriptionService->subscribe($subscription,$request->user());
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param CancelRequest $request
     * @param UserSubscription $userSubscription
     * @return JsonResponse
     */
    public function cancel(CancelRequest $request,UserSubscription $userSubscription):JsonResponse
    {
        $response = $this->userSubscriptionService->cancel($userSubscription);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param PayRequest $request
     * @param UserSubscription $userSubscription
     * @return JsonResponse
     */
    public function pay(PayRequest $request,UserSubscription $userSubscription):JsonResponse
    {
        $response = $this->userSubscriptionService->pay($request->user(),$userSubscription);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }
}
