<?php

namespace Newnet\Tag\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class TagResource extends JsonResource
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
      'slug' => Common::buildSlug($this->slug),
      'name' => $this->name,
      'description' => $this->description,
      'meta' => new SeoResource($this->seometa, $this),
    ];
  }
}
