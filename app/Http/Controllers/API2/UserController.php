<?php

namespace App\Http\Controllers\API2;

use App\Http\Controllers\Controller;
use App\Http\Requests\API2\User\SubscribeRequest;
use App\Http\Traits\HttpResponse;
use App\Interfaces\IUserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use HttpResponse;

    private IUserService $userService;

    /**
     * @param IUserService $userService
     */
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param SubscribeRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function subscribe(SubscribeRequest $request,User $user):JsonResponse
    {
        $response = $this->userService->subscribe($request->renewal_at,$user);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }
}
