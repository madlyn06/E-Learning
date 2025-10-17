<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Newnet\Cms\Utils\StringUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class CategoryDetailResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'content' => $this->content,
      'short_description' => Str::words($this->content, 250),
      'thumbnail' => $this->thumbnail ? $this->thumbnail->url : null,
      'alt' => $this->thumbnail ? $this->thumbnail->alt : null,
      'img_description' => $this->thumbnail ? $this->thumbnail->description : null,
      'slug' => Common::buildSlug($this->url),
      'meta' => new SeoResource($this->seometa, $this),
    ];
  }
}
