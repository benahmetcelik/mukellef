<?php

namespace App\Http\Controllers\API2;

use App\Http\Controllers\Controller;
use App\Http\Requests\API2\User\DeleteSubscriptionRequest;
use App\Http\Requests\API2\User\ListSubscriptionRequest;
use App\Http\Requests\API2\User\SubscribeRequest;
use App\Http\Requests\API2\User\TransactionRequest;
use App\Http\Requests\API2\User\UpdateSubscriptionRequest;
use App\Http\Requests\Subscription\CreateRequest;
use App\Http\Requests\Subscription\IndexRequest;
use App\Http\Requests\Subscription\UpdateRequest;
use App\Http\Traits\HttpResponse;
use App\Interfaces\ISubscriptionService;
use App\Interfaces\ISubscriptionV2Service;
use App\Interfaces\IUserService;
use App\Models\Subscription;
use App\Models\SubscriptionV2;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class SubscriptionController extends Controller
{
    use HttpResponse;

    private ISubscriptionV2Service $subscriptionService;

    /**
     * @param ISubscriptionV2Service $subscriptionService
     */
    public function __construct(ISubscriptionV2Service $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param ListSubscriptionRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function index(ListSubscriptionRequest $request,User $user):JsonResponse
    {
        $response = $this->subscriptionService->index($user,$request->per_page ?? 10);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param SubscribeRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function subscribe(SubscribeRequest $request, User $user): JsonResponse
    {
        $response = $this->subscriptionService->subscribe($request->renewal_at, $user);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param UpdateSubscriptionRequest $request
     * @param User $user
     * @param SubscriptionV2 $subscription
     * @return JsonResponse
     */
    public function update(UpdateSubscriptionRequest $request, User $user, SubscriptionV2 $subscription): JsonResponse
    {
        $response = $this->subscriptionService->update(
            subscriptionV2: $subscription,
            renewal_at: $request->renewal_at,
            user: $user
        );
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param DeleteSubscriptionRequest $request
     * @param User $user
     * @param SubscriptionV2 $subscription
     * @return JsonResponse
     */
    public function delete(DeleteSubscriptionRequest $request, User $user, SubscriptionV2 $subscription): JsonResponse
    {
        $response = $this->subscriptionService->delete(
            subscriptionV2: $subscription
        );
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param TransactionRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function transaction(TransactionRequest $request, User $user):JsonResponse
    {
        $response = $this->subscriptionService->transaction(
            user: $user,
            subscription_id: $request->subscription_id,
            price: $request->price
        );
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }
}
