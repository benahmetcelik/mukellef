<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexAndRequest;
use App\Http\Requests\User\IndexWithRequest;
use App\Http\Traits\HttpResponse;
use App\Interfaces\ISubscriptionService;
use App\Interfaces\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param IndexAndRequest $request
     * @return JsonResponse
     */
    public function indexAnd(IndexAndRequest $request): JsonResponse
    {
        $filters = $request->filters;
        $perPage = $request->per_page ?? 10;
        $response = $this->userService->indexAnd($filters, $perPage);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }

    /**
     * @param IndexWithRequest $request
     * @return JsonResponse
     */
    public function indexWith(IndexWithRequest $request): JsonResponse
    {
        $filters = $request->filters;
        $perPage = $request->per_page ?? 10;
        $response = $this->userService->indexWith($filters, $perPage);
        return $this->httpResponse(isSuccess: $response->getIsSuccess(),
            message: $response->getMessage(),
            data: $response->getData(),
            statusCode: $response->getStatusCode()
        );
    }
}
