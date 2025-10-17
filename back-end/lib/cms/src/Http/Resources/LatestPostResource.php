<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Newnet\Core\Utils\Common;

class LatestPostResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $category = $this->categories()->first();
    return [
      'id' => $this->id,
      'name' => $this->name,
      'url' => $this->url,
      'image' => $this->image ? $this->image->url : '',
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'created_at' => $this->created_at->toFormattedDateString(),
      'author' => [
        'name' => $this->author ? $this->author->name : null,
        'slug' =>  $this->author ? $this->author->slug : null,
        'avatar' => $this->author && $this->author->avatar ? $this->author->avatar->url : null
      ],
      'category' =>[
        'name' => count($this->categories) > 0 ? $category->name : null,
        'slug' => count($this->categories) > 0 ? Common::buildSlug($category->url) : null,
      ],
      'slug' => Common::buildSlug($this->url),
    ];
  }
}
