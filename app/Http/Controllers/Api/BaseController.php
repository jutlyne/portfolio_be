<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Return a successful JSON response.
     *
     * @param array $data The data to be returned in the response.
     * @param int $statusCode The HTTP status code for the response (default is 200).
     * @param string|null $message An optional message to include in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    protected function successResponse($data = [], $message = null, $statusCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'data' => $data,
            'status' => $statusCode,
        ];

        if (!empty($message)) {
            $response['messages'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a failed JSON response.
     *
     * @param int $statusCode The HTTP status code for the response (default is 200).
     * @param string|null $message An optional message to include in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    protected function failedResponse($message = null, $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'status' => $statusCode,
        ];

        if (!empty($message)) {
            $response['messages'] = $message;
        }

        return response()->json($response, $statusCode);
    }
}
