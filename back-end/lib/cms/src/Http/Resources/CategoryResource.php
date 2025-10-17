<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Cms\Utils\StringUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class CategoryResource extends JsonResource
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
      'slug' => Common::buildSlug($this->url),
      'counts' => $this->posts()->where('is_active', true)->count(),
      'icon' => $this->icon,
      'description' => $this->description,
      'content' => StringUtils::replaceImgElement($this->content),
      'image' => $this->image ? $this->image->url : '',
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'meta' => new SeoResource($this->seometa, $this),
    ];
  }
}
