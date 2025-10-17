<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Http\Resources\ProductResource;
use Modules\Ecommerce\Services\CategoryService;

class CategoryController extends Controller
{
  protected $categoryService;

  public function __construct(CategoryService $categoryService)
  {
    $this->categoryService = $categoryService;
  }

  /**
   * Get all active categories
   */
  public function getAllActiveCategories()
  {
    return $this->categoryService->getCategories();
  }

  /**
   * Get products in category
   */
  public function getProductsInCategory($categoryId)
  {
    $category = $this->categoryService->getCategoryById($categoryId);
    return ProductResource::collection($category->products->get() ?? []);
  }
}
