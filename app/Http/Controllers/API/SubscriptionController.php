<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\CreateRequest;
use App\Http\Requests\Subscription\IndexRequest;
use App\Http\Requests\Subscription\UpdateRequest;
use App\Http\Traits\HttpResponse;
use App\Interfaces\ISubscriptionService;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;


class SubscriptionController extends Controller
{
    use HttpResponse;

    private ISubscriptionService $subscriptionService;

    /**
     * @param ISubscriptionService $subscriptionService
     */
    public function __construct(ISubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $filters = $request->filters;
        $perPage = $request->per_page ?? 10;
        $response = $this->subscriptionService->index($filters, $perPage);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }


    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $response = $this->subscriptionService->store(
            $request->name,
            $request->price,
            $request->remaining_limit,
            $request->period
        );
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param UpdateRequest $request
     * @param Subscription $subscription
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Subscription $subscription): JsonResponse
    {
        $response = $this->subscriptionService->update(
            $subscription,
            $request->name,
            $request->price,
            $request->remaining_limit,
            $request->period
        );
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }


    /**
     * @param Subscription $subscription
     * @return JsonResponse
     */
    public function show(Subscription $subscription): JsonResponse
    {
        $response = $this->subscriptionService->show($subscription);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }


    /**
     * @param Subscription $subscription
     * @return JsonResponse
     */
    public function destroy(Subscription $subscription): JsonResponse
    {
        $response = $this->subscriptionService->destroy($subscription);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }
}
