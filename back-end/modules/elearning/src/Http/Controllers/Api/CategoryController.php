<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Elearning\Interfaces\CategoryServiceInterface;

class CategoryController extends BaseController
{
    public function __construct(
        private CategoryServiceInterface $categoryService
    )
    {}

    public function index(): JsonResponse
    {
        return $this->successResponse($this->categoryService->buildTree(), 'Categories retrieved successfully');
    }
}
