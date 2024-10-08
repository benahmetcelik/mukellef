<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponse
{

    /**
     * @param bool $isSuccess
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function httpResponse(bool $isSuccess,
    string $message,
    mixed $data,
    int $statusCode
    ): JsonResponse
    {
        return response()->json([
            'message'=>$message,
            'data'=>$data,
            'isSuccess'=>$isSuccess
        ],$statusCode);
    }
}
