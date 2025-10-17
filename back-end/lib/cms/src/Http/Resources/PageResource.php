<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Cms\Utils\StringUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class PageResource extends JsonResource
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
      'title' => $this->name,
      'slug' => Common::buildSlug($this->url),
      'description' => $this->description,
      'content' => StringUtils::replaceImgElement($this->content),
      'author' => [
        'name' => $this->author ? $this->author->name : null,
        'slug' =>  $this->author ? $this->author->slug : null,
        'avatar' => $this->author && $this->author->avatar ? $this->author->avatar->url : null
      ],
      'meta' => new SeoResource($this->seometa, $this),
      'image' => $this->image ? $this->image->url : null,
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'created_at' => $this->created_at->toFormattedDateString(),
    ];
  }
}
