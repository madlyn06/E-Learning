<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Newnet\Core\Utils\Common;

class PostInCategoryResource extends JsonResource
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
      'description' => $this->description ?? Str::wordWrap(strip_tags($this->content)),
      'slug' => Common::buildSlug($this->url),
      'created_at' => $this->created_at->format('Y-m-d H:i:s'),
    ];
  }
}
