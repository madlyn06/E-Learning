<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
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
      'content' => StringUtils::replaceImgElement($this->content),
      'thumbnail' => $this->thumbnail ? $this->thumbnail->url : null,
      'alt' => $this->thumbnail ? $this->thumbnail->alt : null,
      'description_thumb' => $this->thumbnail ? $this->thumbnail->description : null,
      'url' => Common::buildSlug($this->url),
      'meta' => new SeoResource($this->seometa, $this),
    ];
  }
}
