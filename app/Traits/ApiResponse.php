<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{

    /**
     * Success message
     * 
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */

    public function successResponse(string $message, mixed $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'statusCode' =>$statusCode
        ], $statusCode);
    }

    /**
     * Error message
     * 
     * @param string $message
     * @param mixed $error
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */

    public function errorResponse(string $message, int $statusCode = 500, array $errors = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'errors' => $errors
        ], $statusCode);
    }
}
