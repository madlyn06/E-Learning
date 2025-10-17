<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Ecommerce\Http\Resources\ProductDetailResource;
use Modules\Ecommerce\Http\Resources\ProductResource;
use Modules\Ecommerce\Models\Product;
use Modules\Ecommerce\Services\ProductService;
use Newnet\Cms\Utils\EloquentUtils;

class ProductController extends Controller
{
  protected $productService;

  public function __construct(ProductService $productService)
  {
    $this->productService = $productService;
  }

  /**
   * Get all products
   */
  public function getAllProducts()
  {
    return ProductResource::collection(Product::all());
  }

  /**
   * Get list products
   */
  public function getProducts()
  {
    $limit = request('limit');
    return $this->productService->getProducts($limit);
  }

  /**
   * Get product detail
   * @param integer $productId
   */
  public function getProductDetail($productId)
  {
    // Find with product id first, if not found, found by slug
    $product = Product::find($productId);
    if ($product) {
      return new ProductDetailResource($product);
    }
    $item = EloquentUtils::transferModel($productId);
    if (!$item) {
      return response()->json([
        'message' => 'Product not found',
      ], 400);
    }
    return new ProductDetailResource($item);
  }

  /**
   * Retrieve products in a specific category by slug.
   *
   * @param string $slug The slug of the category.
   * @return \Illuminate\Http\JsonResponse A JSON response containing the products in the specified category.
   */
  public function getProductsInCategory($slug): JsonResponse|array
  {
    $limit = request('limit');
    return $this->productService->getProductsInCategory($slug, $limit);
  }

  /**
   * Retrieve related products based on the given product slug.
   *
   * @param string $slug The slug of the product to find related products for.
   * @return \Illuminate\Http\JsonResponse A JSON response containing the related products.
   */
  public function getRelatedProducts($productId)
  {
    return $this->productService->getRelatedProducts($productId);
  }
}
