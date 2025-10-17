<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class ProductDetailResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $ratings = $this->ratings()->where('is_published', true)->orderBy('id', 'DESC');

    return [
      'id' => $this->id,
      'name' => $this->name,
      'origin_price' => format_money($this->origin_price),
      'sale_price' => format_money($this->sale_price),
      'description' => $this->description,
      'content' => $this->content,
      'image' => $this->image->url ?? null,
      'image_alt' => $this->image->alt ?? null,
      'image_des' => $this->image->description ?? null,
      'gallery' => $this->gallery && $this->gallery->count() > 0 ? GalleryResource::collection($this->gallery) : [],
      'categories' => CategoryResource::collection($this->categories ?? []),
      'related_products' => ProductResource::collection($this->related_products ?? []),
      'slug' => Common::buildSlug($this->url),
      'meta' => new SeoResource($this->seometa, $this),
      'ratings' => RatingResource::collection($ratings->paginate(setting('item_on_page', 10)) ?? [])->response()->getData(true),
    ];
  }
}
