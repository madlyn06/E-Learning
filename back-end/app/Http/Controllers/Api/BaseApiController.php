<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

abstract class BaseApiController extends Controller
{
    /**
     * Example of using the trait methods
     */
    protected function exampleUsage()
    {
        // Using trait methods (inherited from Controller)
        return $this->successResponse(['data' => 'example'], 'Success message');
        
        // Using static response class
        return ApiResponse::success(['data' => 'example'], 'Success message');
    }

    /**
     * Example of different response types
     */
    protected function exampleResponses()
    {
        // Success responses
        $this->successResponse(['id' => 1], 'Created successfully');
        $this->createdResponse(['id' => 1], 'Resource created');
        $this->updatedResponse(['id' => 1], 'Resource updated');
        $this->deletedResponse('Resource deleted');

        // Error responses
        $this->errorResponse('Something went wrong', 400);
        $this->notFoundResponse('User not found');
        $this->unauthorizedResponse('Please login');
        $this->forbiddenResponse('Access denied');
        $this->serverErrorResponse('Database connection failed');

        // Validation errors
        $this->validationErrorResponse(['field' => ['Error message']], 'Validation failed');
    }
}
