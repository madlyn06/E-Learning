<?php

namespace Modules\Ecommerce\Services;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ecommerce\Exceptions\ProductException;
use Modules\Ecommerce\Http\Resources\CategoryDetailResource;
use Modules\Ecommerce\Http\Resources\ProductDetailResource;
use Modules\Ecommerce\Http\Resources\ProductResource;
use Modules\Ecommerce\Models\Category;
use Modules\Ecommerce\Models\Product;
use Newnet\Seo\Models\Url;

class ProductService
{
  /**
   * Get list products
   * @param integer $limit
   */
  public function getProducts($limit = 16)
  {
    $sort = request('orderby');
    if (!$sort) {
      $sortName = 'created_at';
      $sortValue = 'asc';
    } else {
      $sortName = explode('-', $sort)[0];
      $sortValue = explode('-', $sort)[1];
    }
    $products = Product::whereIsActive(true)->orderBy($sortName, $sortValue)->paginate($limit);
    return ProductResource::collection($products ?? [])->response()->getData(true);
  }

  /**
   * Get product detail
   * @param interger $productId
   */
  public function getProductDetail($productId)
  {
    $product = Product::find($productId);
    return new ProductDetailResource($product);
  }

  /**
   * Retrieves a list of products within a specified category.
   *
   * @param string $slug The slug identifier for the category.
   * @param int $limit The maximum number of products to retrieve. Default is 12.
   * @return array The list of products in the specified category.
   */
  public function getProductsInCategory($slug, $limit = 16)
  {
    $sort = request('orderby');
    if (!$sort) {
      $sortName = 'created_at';
      $sortValue = 'asc';
    } else {
      $sortName = explode('-', $sort)[0];
      $sortValue = explode('-', $sort)[1];
    }
    $url = Url::where(['urlable_type' => Category::class, 'request_path' => $slug])->first();
    if (!$url) {
      throw new ProductException('Could not find category');
    }
    $category = Category::find($url->urlable_id);
    if (!$category) {
      throw new ProductException('Could not find category');
    }
    $products = $category->products()->orderBy($sortName, $sortValue)->paginate($limit);
    return [
      'category' => new CategoryDetailResource($category),
      'products' => ProductResource::collection($products ?? [])->response()->getData(true),
    ];
  }

  /**
   * Retrieves related products based on the provided product productId.
   *
   * @param string $slug The slug of the product for which related products are to be fetched.
   * @return array An array of related products.
   */
  public function getRelatedProducts($productId): AnonymousResourceCollection|array
  {
    $product = Product::find($productId);
    if (!$product) {
      throw new ProductException('Could not find product');
    }
    $catIds = $product->categories->pluck('id');
    $relatedPosts = null;
    if ($catIds->count() > 0) {
      $relatedPosts = Product::whereHas('categories', function ($query) use ($catIds) {
        $query->whereIn('id', $catIds);
      })->where('id', '!=', $product->id)->inRandomOrder()->limit(2)->get();
    }
    return ProductResource::collection($relatedPosts ?? []);
  }
}
