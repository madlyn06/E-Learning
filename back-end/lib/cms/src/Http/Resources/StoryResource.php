<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class StoryResource extends JsonResource
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
      'created_at' => $this->created_at->format('d-m-Y'),
      'slug' => $this->slug,
      'image' => $this->image ? $this->image->url : '',
      'meta' => new SeoResource($this->seometa, $this),
      'origin_post_url' => $this->post ? Common::buildSlug($this->post->url) : '/',
    ];
  }
}
