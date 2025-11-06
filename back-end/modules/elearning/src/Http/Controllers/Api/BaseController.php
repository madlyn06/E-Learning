<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use App\Http\Responses\ApiResponse;

class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Success response
     */
    public function successResponse($data = null, $message = 'Success', $statusCode = 200)
    {
        return ApiResponse::success($data, $message, $statusCode);
    }

    /**
     * Created response
     */
    public function createdResponse($data = null, $message = 'Resource created successfully')
    {
        return ApiResponse::created($data, $message);
    }

    /**
     * Updated response
     */
    public function updatedResponse($data = null, $message = 'Resource updated successfully')
    {
        return ApiResponse::updated($data, $message);
    }

    /**
     * Deleted response
     */
    public function deletedResponse($message = 'Resource deleted successfully')
    {
        return ApiResponse::deleted($message);
    }

    /**
     * Error response
     */
    public function errorResponse($message = 'Error occurred', $statusCode = 400, $errors = null)
    {
        return ApiResponse::error($message, $statusCode, $errors);
    }

    /**
     * Validation error response
     */
    public function validationErrorResponse($errors, $message = 'Validation failed')
    {
        return ApiResponse::validationError($errors, $message);
    }

    /**
     * Not found response
     */
    public function notFoundResponse($message = 'Resource not found')
    {
        return ApiResponse::notFound($message);
    }

    /**
     * Unauthorized response
     */
    public function unauthorizedResponse($message = 'Unauthorized')
    {
        return ApiResponse::unauthorized($message);
    }

    /**
     * Forbidden response
     */
    public function forbiddenResponse($message = 'Forbidden')
    {
        return ApiResponse::forbidden($message);
    }

    /**
     * Server error response
     */
    public function serverErrorResponse($message = 'Internal server error')
    {
        return ApiResponse::serverError($message);
    }

    /**
     * Paginated response
     */
    public function paginatedResponse($data, $message = 'Data retrieved successfully')
    {
        return ApiResponse::success([
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ], $message);
    }
}
