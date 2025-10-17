<?php

namespace Modules\Ecommerce\Services;

use Modules\Ecommerce\Http\Resources\CategoryResource;
use Modules\Ecommerce\Models\Category;

class CategoryService
{
  /**
   * Get category by id
   */
  public function getCategoryById($id)
  {
    return Category::find($id);
  }

  /**
   * Get all active category
   */
  public function getCategories()
  {
    $categories = Category::whereIsActive(true)->get();
    return CategoryResource::collection($categories ?? []);
  }
}
