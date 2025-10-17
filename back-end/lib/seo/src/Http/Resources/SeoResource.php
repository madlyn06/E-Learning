<?php

namespace Newnet\Seo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SeoResource extends JsonResource
{
  protected $parentEntity;

  public function __construct($resource, $parentEntity = null)
  {
      parent::__construct($resource);
      $this->parentEntity = $parentEntity;
  }
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $parentEntity = $this->parentEntity;
    return [
      'title' => $this->title,
      'description' => $this->description ?: Str::words($parentEntity->content),
      'keywords' => $this->keywords,
      'robots' => $this->robots,
      'canonical' => $this->canonical,
      'og_title' => $this->og_title ?: $parentEntity->name ?: $parentEntity->title,
      'og_description' => $this->og_description ?: $this->description ?: Str::words($parentEntity->content),
      'og_image' => $this->og_image ? $this->og_image->url : '',
      'twitter_title' => $this->twitter_title ?: $parentEntity->name ?: $parentEntity->title,
      'twitter_description' => $this->twitter_description ?: $this->description ?: Str::words($parentEntity->content),
      'twitter_image' => $this->twitter_image ? $this->twitter_image->url : '',
    ];
  }
}
