<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    /**
     * Success Response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ], $statusCode);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', int $statusCode = Response::HTTP_BAD_REQUEST, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation Error Response
     *
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function validationError($errors, string $message = 'Validation failed'): JsonResponse
    {
        return self::error($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Not Found Response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Unauthorized Response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Forbidden Response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Server Error Response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return self::error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Created Response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Updated Response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function updated($data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_OK);
    }

    /**
     * Deleted Response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return self::success(null, $message, Response::HTTP_OK);
    }
}
